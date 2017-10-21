<?php

namespace SfRestApi\Contracts;

class RequestInterface
{
    /**
     * @returns ClientConfig
     */
    public function getConfig(){};

    /**
     * @returns array
     */
    protected function getHeaders(){};
}