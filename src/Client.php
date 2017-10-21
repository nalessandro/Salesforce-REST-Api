<?php

namespace SfRestApi;

use SfRestApi\Request\ClientConfig;
use SfRestApi\Request\Request;

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
}