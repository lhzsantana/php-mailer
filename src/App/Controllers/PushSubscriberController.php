<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class PushSubscriberController
{

    protected $pushSubscriber;

    public function __construct($service)
    {
        $this->pushSubscriber = $service;
    }

    public function getOne($id)
    {
        return new JsonResponse($this->pushSubscriber->getOne($id));
    }

    public function delete($id)
    {
        return new JsonResponse($this->pushSubscriber->delete($id));
    }
}
