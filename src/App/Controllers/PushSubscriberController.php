<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;

class PushSubscriberController
{
    protected $app;
    protected $pushSubscriber;
    protected $server;
    protected $response;

    public function __construct($service, $app)
    {
        $this->pushSubscriber = $service;
        $this->app = $app;
        $this->server = $this->app['oauth_server'];
        $this->response = $this->app['oauth_response'];
    }

    public function getOne($id)
    {
        if (!$this->checkAuth()) {
            return $this->server->getResponse();
        } else {
            return new JsonResponse($this->pushSubscriber->getOne($id));
        }
    }

    public function delete($id)
    {
        if (!$this->checkAuth()) {
            return $this->server->getResponse();
        } else {
            return new JsonResponse($this->pushSubscriber->delete($id));
        }
    }

    private function checkAuth(){
        return $this->server->verifyResourceRequest($this->app['request_stack']->getCurrentRequest(), $this->response);
    }
}
