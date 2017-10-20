<?php

namespace SfRestApi;

use SfRestApi\Request\ClientConfig;
use SfRestApi\Contracts\ClientConfigInterface;

/**
 * Class Bulk
 * @package SfRestApi
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
class Bulk
{
    /**
     * @var ClientConfig
     */
    protected $client_config;

    public function __construct(ClientConfigInterface $config)
    {
        $this->client_config = $config;
    }
}