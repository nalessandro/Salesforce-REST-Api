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
    public $crud;

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
     * @param String $name
     * @param String $params
     *
     * @return string
     */
    public function __call(String $name, String $params):string {
        $this->crud->$name($params);
    }
}