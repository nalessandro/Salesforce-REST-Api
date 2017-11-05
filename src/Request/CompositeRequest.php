<?php

namespace SfRestApi\Request;

use SfRestApi\Contracts\RequestInterface;

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
class CompositeRequest extends BaseRequest implements RequestInterface
{
    public static $_instance;

    public $requestUri = '/composite';

    public static function getInstance() {
        if( !isset( self::$_instance )) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function composite( string $args ): \stdClass {
        return $this->makeRequest( json_decode( $args ));
    }

    public function query( string $q ): \stdClass {}

    public function insert ( string $args ): \stdClass {
        // TODO: Implement insert() method.
    }

    public function update ( string $args ): \stdClass {
        // TODO: Implement update() method.
    }

    public function delete ( string $args ): \stdClass {
        // TODO: Implement delete() method.
    }

    /**
     * Preps multiple records to be sent in a Batch
     *
     * @todo Refactor for composite requests
     *
     * @param String $method
     * @param array  $args
     *
     * @return array
     */
    public function prepRequest( String $method, array $args ) {

        $i=0;
        $batched = array();
        $r1 = array();
        foreach($args as $r) {
            $r['method'] = $method;
            $r['url'] = $this->getConfig()->getApiVersion() . $this->requestUri . $args['object'] . '/' . $r['id'];
            $r['richInput'] = $r;
            $r1[] = $r;
            $i++;

            if($i%200 == 0) {
                $batched[] = $r1;
                $r1 = array();
            }
        }

        return $batched;
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
    protected function makeRequest(string $reqJson): \stdClass {
        $response = $this->send('POST'
            ,$this->getConfig()->getBaseUri().$this->requestUri
            ,$reqJson
        );
        return json_decode( $response );
    }
}