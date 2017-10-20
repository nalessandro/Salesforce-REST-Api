<?php

namespace SfRestApi;

use SfRestApi\Request\Request;
use SfRestApi\Contracts\ClientConfigInterface;

/**
 * Class Rest
 * @package SfRestApi
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
class Rest extends Request
{
    public function __construct(ClientConfigInterface $config)
    {
        parent::__construct($config);
    }

    /**
     * @param String $query
     * @return \stdClass
     * @thows \Exception
     */
    public function query(String $query):\stdClass
    {
        $results = $this->makeRequest();
        return $results;
    }
}