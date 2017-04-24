<?php

require_once __DIR__ . '/../vendor/autoload.php';

define("ROOT_PATH", __DIR__ . "/..");

$app = new Silex\Application();

require __DIR__ . '/../resources/config/prod.php';

require __DIR__ . '/../src/app.php';

// create an http foundation request implementing OAuth2\RequestInterface
$request = OAuth2\HttpFoundationBridge\Request::createFromGlobals();
$app->run($request);
//$app['http_cache']->run();
