<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class NotificationController
{
    protected $notificationService;

    public function __construct($service)
    {
        $this->notificationService = $service;
    }

    public function getOne($id)
    {
        return new JsonResponse($this->notificationService->getOne($id));
    }

    public function getAll()
    {
        return new JsonResponse($this->notificationService->getAll());
    }

    public function save(Request $request)
    {
        $email = $this->getDataFromRequest($request);

        $this->notificationService->save($email);

        return new JsonResponse($email);
    }

    public function delete($id)
    {
        return new JsonResponse($this->notificationService->delete($id));
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
}
