<?php

namespace App\Services\DataFormats;

class ArrayDataFormat extends AbstractDataFormat
{

    public function getData()
    {
        if (is_array($this->data)) {
            return $this->data;
        }

        if (($data = json_decode($this->data, true)) && json_last_error() === JSON_ERROR_NONE) {
            return $data;
        }

        if (is_string($this->data)) {
            $data = explode("\n", $this->data);
            $data = array_filter($data, function ($element) {
                return $element !== '';
            });

            return $data;
        }

        return false;
    }
}
