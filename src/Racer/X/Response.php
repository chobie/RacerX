<?php
namespace Racer\X;

class Response
{
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __toString()
    {
        return $this->data;
    }
}