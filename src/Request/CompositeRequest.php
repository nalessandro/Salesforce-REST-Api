<?php

namespace SfRestApi\Request;

use SfRestApi\Contracts\CompositeInterface;

/**
 * Class CompositeRequest
 * ------------------------------------------
 * Executes a series of REST API requests in a single call. You can use
 * the output of one request as the input to a subsequent request. The
 * response bodies and HTTP statuses of the requests are returned in a single
 * response body. The entire request counts as a single call toward your API limits.
 * @link https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/resources_composite_composite.htm
 *
 * @todo ADD ERROR HANDLING
 * @todo HANDLE MORE THAN 200 RECORDS
 *
 * @package SfRestApi\Request
 */
class CompositeRequest extends BaseRequest implements CompositeInterface
{
    public static $_instance;

    public $requestUri = '/composite';

    public static function getInstance() {
        if( !isset( self::$_instance )) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Composite Request
     * ------------------------------------------
     * Executes a series of REST API requests in a single call. You can use the output of one request as the input to a 
     * subsequent request. The response bodies and HTTP statuses of the requests are returned in a single response body. The 
     * entire request counts as a single call toward your API limits.
     * @link https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/requests_composite.htm
     * 
     * Sample JSON:
     * {
     *     "method" : "GET",
     *     "url" : "/services/data/v38.0/sobjects/Account/describe",
     *     "httpHeaders" : { "If-Modified-Since" : "Tue, 31 May 2016 18:00:00 GMT" },   
     *     "referenceId" : "AccountInfo"
     * },{
     *     "method" : "POST",
     *     "url" : "/services/data/v38.0/sobjects/Account",
     *     "referenceId" : "refAccount",
     *     "body" : { "Name" : "Sample Account" }
     * }
     * 
     * @param  string $args [description]
     * @return [type]       [description]
     */
    public function request( string $args ): \stdClass {
        return $this->makeRequest( json_decode( $args ));
    }

    /**
     * Make Request
     * ------------------------------------------
     * Forwards request on to Salesforce
     *
     * @param string $reqJson
     *
     * @return \stdClass
     */
    protected function makeRequest( array $args ): \stdClass {
        $response = $this->send('POST'
            ,$this->getConfig()->getBaseUri().$this->requestUri
            ,$reqJson
        );
        return json_decode( $response );
    }
}