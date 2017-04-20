<?php

namespace App\Services;

class NotificationService extends BaseService
{
    private $PREFIX='NOTIFICATION:';

    public function getOne($id)
    {
        return json_decode($this->predis->get($this->PREFIX.$id));
    }

    public function getAll()
    {
        $result = array();

        foreach ($this->predis->keys("*") as &$key) {
            $result[] = json_decode($this->predis->get($this->PREFIX.$key));
        }

        return $result;
    }

    function save($notification)
    {
        return $this->predis->set($this->PREFIX.$notification["uuid"], json_encode($notification));
    }

    function delete($id)
    {
        return $this->predis->del($this->PREFIX.$id);
    }

}
