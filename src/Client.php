<?php

namespace SfRestApi;

use SfRestApi\Request\ClientConfig;
use SfRestApi\Request\BaseRequest;
use SfRestApi\Factory\RequestFactory;

/**
 * Class SalesforceClient
 * Based on the instatiated instance forwards all requests to the
 * correct classes.
 *
 * Converts objects & associative arrays into JSON before processing.
 *
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

    /**
     * Returns the initialized configuration
     *
     * @return Contracts\ClientConfigInterface
     */
    public function getConfig() {
        return self::$_instance->getConfig();
    }

    /**
     * Returns the instantiated instance
     *
     * @return Request|BatchRequest|CompositeRequest|TreeRequest
     */
    public function getInstance() {
        return self::$_instance;
    }

    /**
     * Magic Method to handle standard CRUD calls and forward
     * on to instance specific logic
     *
     * @param string $method    query|insert|update|delete
     * @param array $params
     *
     * @return string
     */
    public function __call(string $method, array $params) {
        return self::$_instance->$method( json_encode( $params[0] ) );
    }
}