<?php

namespace SfRestApi;

use SfRestApi\Request\ClientConfig;
use SfRestApi\Request\BaseRequest;
use SfRestApi\Factory\RequestFactory;

/**
 * Class SalesforceClient
 * @package SfRestApi
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
class Client
{
    protected static $_instance;

    /**
     * Client constructor.
     *
     * @param string $jsonParam
     * @param String $type
     */
    public function __construct( string $jsonParam, String $type = '' ) {
        BaseRequest::init( new ClientConfig( json_decode($jsonParam) ) );
        self::$_instance = RequestFactory::init($type);
    }

    public function getConfig() {
        return self::$_instance->getConfig();
    }

    public function getInstance() {
        return self::$_instance;
    }

    /**
     * @param string $name
     * @param array $params
     *
     * @return string
     */
    public function __call(string $name, array $params) {
        return self::$_instance->$name( $params[0] );
    }
}