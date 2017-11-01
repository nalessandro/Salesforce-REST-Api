<?php

namespace SfRestApi;

use SfRestApi\Request\ClientConfig;
use SfRestApi\Request\BaseRequest;

/**
 * Class SalesforceClient
 * @package SfRestApi
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
class Client
{
    /**
     * Client constructor.
     * @param String $jsonParam
     */
    public function __construct( String $jsonParam ) {
        BaseRequest::init( new ClientConfig( json_decode($jsonParam) ) );
    }

    /**
     * @param string $name
     * @param array $params
     *
     * @return string
     */
    public function __call(string $name, array $params) {

        $params[0]['method'] = $name;
        $crud = CRUD::init();
        $crud->process( json_decode( $params[0] ) );
    }
}