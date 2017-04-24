<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class NotificationController
{
    protected $notificationService;
    protected $server;
    protected $response;

    public function __construct($service, $app)
    {
        $this->notificationService = $service;
        $this->app = $app;
        $this->server = $this->app['oauth_server'];
        $this->response = $this->app['oauth_response'];
    }

    public function getOne($id)
    {
        if (!$this->checkAuth()) {
            return $this->server->getResponse();
        } else {
            return new JsonResponse($this->notificationService->getOne($id));
        }
    }

    public function getAll()
    {
        if (!$this->checkAuth()) {
            return $this->server->getResponse();
        } else {
            return new JsonResponse($this->notificationService->getAll());
        }
    }

    public function save(Request $request)
    {
        if (!$this->checkAuth()) {
            return $this->server->getResponse();
        } else {
            $email = $this->getDataFromRequest($request);

            $this->notificationService->save($email);

            return new JsonResponse($email);
        }
    }

    public function delete($id)
    {
        if (!$this->checkAuth()) {
            return $this->server->getResponse();
        } else {
            return new JsonResponse($this->notificationService->delete($id));
        }
    }

    public function getDataFromRequest(Request $request)
    {
        return $email = array(
            "uuid" =>  uniqid(),
            "message" => $request->request->get("message"),
            "subject" => $request->request->get("subject"),
            "subscribers" => $request->request->get("subscribers"),
            "channels" => $request->request->get("channels"),
            "status" => "ADDED"
        );
    }

    private function checkAuth(){
        return $this->server->verifyResourceRequest($this->app['request_stack']->getCurrentRequest(), $this->response);
    }
}
