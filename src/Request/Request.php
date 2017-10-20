<?php

namespace SfRestApi\Request;

use SfRestApi\Contracts\ClientConfigInterface;

/**
 * Class Request
 * @package SfRestApi\Config
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
class Request extends BaseRequest
{
    public function __construct(ClientConfigInterface $config)
    {
        parent::__construct($config);
    }

    protected function makeRequest(String $url)
    {

    }
}