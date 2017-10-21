<?php

namespace SfRestApi;

use SfRestApi\Request\Request;
use SfRestApi\Contracts\ClientConfigInterface;

/**
 * Class CRUD
 * @package SfRestApi
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
class CRUD
{
    public function __construct(ClientConfigInterface $config)
    {
        parent::__construct($config);
    }

    /**
     * @param String $query
     * @return string
     * @thows \Exception
     */
    public function query(String $query): string
    {
        $results = $this->makeRequest('GET'
                        ,$this->config->getBaseUri().'/query?q='.str_replace(' ', '+', $query)
                        ,'');

        return $results;
    }
}