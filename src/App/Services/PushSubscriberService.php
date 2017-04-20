<?php

namespace App\Services;

class PushSubscriberService extends BaseService
{

    public function getOne($id)
    {
        return json_decode($this->predis->get($id));
    }

    function save($notification)
    {
        return $this->predis->set($notification["uuid"], json_encode($notification));
    }

    function delete($id)
    {
        return $this->predis->del($id);
    }

}
