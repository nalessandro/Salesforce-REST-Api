<?php

namespace SfRestApi;

use SfRestApi\Request\ClientConfig;

/**
 * Class SalesforceClient
 * @package SfRestApi
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
class Client
{
    /**
     * @var CRUD
     */
    private $crud;

    /**
     * Client constructor.
     * @param String $jsonParam
     */
    public function __construct( String $jsonParam )
    {
        $params = json_decode($jsonParam);
        $this->crud = new CRUD( new ClientConfig($params) );
    }

    /**
     * @param string $name
     * @param array $params
     *
     * @return string
     */
    public function __call(string $name, array $params): string {
        return $this->crud->$name( $params[0] );
    }
}