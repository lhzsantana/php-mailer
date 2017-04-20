<?php

namespace App\Services;

class BaseService
{
    protected $predis;

    public function __construct($predis)
    {
        $this->predis = $predis;
    }

}
