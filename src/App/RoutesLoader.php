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
        $api1 = $this->app["controllers_factory"];
        $api2 = $this->app["controllers_factory"];

        $api1->get('/authorize', "authorize.controller:authorize");
        $api1->get('/notification', "notification.controller:getAll");
        $api1->get('/notification/{id}', "notification.controller:getOne");
        $api1->post('/notification', "notification.controller:save");
        $api1->delete('/notification/{id}', "notification.controller:delete");

        $api2->get('/notification/subscriber/{id}', "pushSubscriber.controller:getOne");
        $api2->delete('/notification/subscriber/{id}', "pushSubscriber.controller:delete");

        $this->app->mount($this->app["api.endpoint"].'/'.$this->app["api.version1"], $api1);
        $this->app->mount($this->app["api.endpoint"].'/'.$this->app["api.version2"], $api2);
    }
}

