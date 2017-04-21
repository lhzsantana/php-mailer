<?php

namespace App\Services;

class NotificationService extends BaseService
{
    private $PREFIX='NOTIFICATION:';

    public function getOne($id)
    {
        $result["request"] = json_decode($this->predis->get($this->PREFIX.$id));

        $failures = array();

        foreach ($this->predis->keys($id.":FAILURE:*") as &$key) {
            $failures[] = $this->predis->get($key);
        }
        $result["failures"]= $failures;

        return $result;
    }

    public function getAll()
    {
        $result = array();

        foreach ($this->predis->keys("*") as &$key) {
            $result[] = getOne($key);
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
