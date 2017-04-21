<?php

use Silex\Application;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\ServicesLoader;
use App\RoutesLoader;
use Carbon\Carbon;

date_default_timezone_set('Europe/London');

//accepting JSON
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app->register(new \Euskadi31\Silex\Provider\CorsServiceProvider);

$app->register(new ServiceControllerServiceProvider());

$app->register(new Predis\Silex\ClientServiceProvider(), [
    'predis.parameters' => 'tcp://192.168.99.100:6379'
]);

$app->register(new HttpCacheServiceProvider(), array("http_cache.cache_dir" => ROOT_PATH . "/storage/cache",));

$app->register(new MonologServiceProvider(), array(
    "monolog.logfile" => ROOT_PATH . "/storage/logs/" . Carbon::now('Europe/London')->format("Y-m-d") . ".log",
    "monolog.level" => $app["log.level"],
    "monolog.name" => "application"
));

//load services
$servicesLoader = new App\ServicesLoader($app);
$servicesLoader->bindServicesIntoContainer();

//load routes
$routesLoader = new App\RoutesLoader($app);
$routesLoader->bindRoutesToControllers();

$app->error(function (\Exception $e, $code) use ($app) {
    $app['monolog']->addError($e->getMessage());
    $app['monolog']->addError($e->getTraceAsString());
    return new JsonResponse(array("statusCode" => $code, "message" => $e->getMessage(), "stacktrace" => $e->getTraceAsString()));
});

$app->register(new AuthBucket\OAuth2\Provider\AuthBucketOAuth2ServiceProvider());

$app->register(new Silex\Provider\SecurityServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());


$app['security.default_encoder'] = function ($app) {
    return new Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder();
};

$app['security.user_provider.default'] = $app['security.user_provider.inmemory._proto']([
    'demousername1' => ['ROLE_USER', 'demopassword1'],
    'demousername2' => ['ROLE_USER', 'demopassword2'],
    'demousername3' => ['ROLE_USER', 'demopassword3'],
]);

$app['security.firewalls'] = [
    'api_oauth2_authorize' => [
        'pattern' => '^/api/oauth2/authorize$',
        'http' => true,
        'users' => $app['security.user_provider.default'],
    ],
];

$app->get('/api/oauth2/authorize', 'authbucket_oauth2.oauth2_controller:authorizeAction')
    ->bind('api_oauth2_authorize');

$app->post('/api/oauth2/token', 'authbucket_oauth2.oauth2_controller:tokenAction')
    ->bind('api_oauth2_token');

$app->match('/api/oauth2/debug', 'authbucket_oauth2.oauth2_controller:debugAction')
    ->bind('api_oauth2_debug');

$app['security.firewalls'] = [
    'api_resource' => [
        'pattern' => '^/api/',
        'oauth2_resource' => true,
    ],
];

return $app;
