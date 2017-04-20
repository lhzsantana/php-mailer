<?php

namespace App;

use Silex\Application;

class ServicesLoader
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function bindServicesIntoContainer()
    {
        $this->app['notification.service'] = function() {
            return new Services\NotificationService($this->app["predis"]);
        };

        $this->app['pushSubscriber.service'] = function() {
            return new Services\PushSubscriberService($this->app["predis"]);
        };
    }
}

