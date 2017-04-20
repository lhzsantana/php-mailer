<?php

namespace App;

use Silex\Application;

class RoutesLoader
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->instantiateControllers();

    }

    private function instantiateControllers()
    {
        $this->app['notification.controller'] = function() {
            return new Controllers\NotificationController($this->app['notification.service']);
        };

        $this->app['pushSubscriber.controller'] = function() {
            return new Controllers\PushSubscriberController($this->app['pushSubscriber.service']);
        };
    }

    public function bindRoutesToControllers()
    {
        $api = $this->app["controllers_factory"];

        $api->get('/notification', "notification.controller:getAll");
        $api->get('/notification/{id}', "notification.controller:getOne");
        $api->post('/notification', "notification.controller:save");
        $api->delete('/notification/{id}', "notification.controller:delete");

        $api->get('/notification/subscriber/{id}', "pushSubscriber.controller:getOne");
        $api->delete('/notification/subscriber/{id}', "pushSubscriber.controller:delete");

        $this->app->mount($this->app["api.endpoint"].'/'.$this->app["api.version"], $api);
    }
}

