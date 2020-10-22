<?php

namespace App\Services\DataFormats;

abstract class AbstractDataFormat
{
    protected $data;


    /**
     * AbstractDataFormat constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }


    /**
     * Get data in certain format
     *
     * @return mixed
     */
    abstract public function getData();
}
