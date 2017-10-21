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
     * @var Bulk
     */
    protected $bulk;

    /**
     * Client constructor.
     *
     * @param String $login_url
     * @param String $username
     * @param String $password
     * @param String $client_id
     * @param String $client_secret
     * @param String $security_token
     */
    public function __construct( String $jsonParam )
    {
        $params = json_decode($jsonParam);
        $this->crud = new CRUD( new ClientConfig($params) );
    }
}