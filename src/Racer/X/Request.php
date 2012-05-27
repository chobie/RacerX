<?php
namespace Racer\X;

class Request
{
    protected $parameters;

    public function __construct($array)
    {
        $this->parameters = $array;
    }

    public function get($key, $default = null)
    {
        if (isset($this->parameters[$key])) {
            return $this->parameters[$key];
        } else {
            return $default;
        }
    }

    public function isGet()
    {
        if (isset($this->parameters['REQUEST_METHOD']) && $this->parameters['REQUEST_METHOD'] == 'GET') {
            return true;
        } else {
            return false;
        }
    }

    public function isPost()
    {
        if (isset($this->parameters['REQUEST_METHOD']) && $this->parameters['REQUEST_METHOD'] == 'POST') {
            return true;
        } else {
            return false;
        }
    }


}