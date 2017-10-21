<?php

namespace SfRestApi\Contracts;

interface RequestInterface
{
    /**
     * @returns ClientConfig
     */
    public function getConfig();
}