<?php

namespace App\Services\DataFormats;

class ArrayDataFormat extends AbstractDataFormat
{
    /**
     * Get data in array format
     *
     * @return mixed
     * @throws \JsonException
     */
    public function getData()
    {
        if (is_array($this->data)) {
            return $this->data;
        }

        if (($data = json_decode($this->data, true, 512, JSON_THROW_ON_ERROR)) && json_last_error() === JSON_ERROR_NONE) {
            return $data;
        }

        if (is_string($this->data)) {
            $data = explode("\n", $this->data);

            return array_filter($data, function ($element) {
                return $element !== '';
            });
        }

        return false;
    }
}
