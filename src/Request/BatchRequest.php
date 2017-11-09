<?php

namespace SfRestApi\Request;

use SfRestApi\Contracts\RequestInterface;

/**
 * Class BatchRequest
 * ------------------------------------------
 * Executes up to 25 subrequests in a single request. The response bodies
 * and HTTP statuses of the subrequests in the batch are returned in a
 * single response body. Each subrequest counts against rate limits.
 * @link https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/resources_composite_batch.htm
 *
 * Salesforce batch requests are only proccessed through input data in the
 * url query string. Therefore, all 'POST' requests will return errors. Will
 * only support QUERY, UPDATE, and DELETE.
 *
 * @todo ADD ERROR HANDLING
 * @todo HANDLE MORE THAN 25 RECORDS
 *
 * @package SfRestApi\Request
 */
class BatchRequest extends BaseRequest implements CompositeInterface
{
    public static $_instance;

    private $requestUri = '/composite/batch';

    public static function getInstance() {
        if( !isset( self::$_instance )) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Batch Request
     * ------------------------------------------
     * Performs a true batch request by allowing varying subrequests to be performed
     * in one request
     * @link https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/requests_composite_batch.htm
     *
     * Required Properties
     *      requests: any mixture of query, update, delete formatted requests
     *
     * @param string $args
     * @return \stdClass
     */
    public function request ( string $args ): \stdClass {
        $req = json_decode( $args );
        return $this->makeRequest(json_encode( ['batchRequests' => $req] ));
    }

    /**
     * Prepare Request
     * ------------------------------------------
     * Format requests into Salesforce batch request body
     *
     * @param string    $method
     * @param \stdClass  $args
     *
     * @return string
     */
    protected function prepRequest (string $method, \stdClass $args): string {

        $r = new \stdClass();
        foreach($args->records as $record) {
            $r->method = $method;
            $r->url = $this->getConfig()->getBaseUri() . '/sobjects/' . $args->object
                    . (property_exists($record, 'Id') ? '/' . $record->Id : '');
            //$r->body = $record;
            if( $method == 'PATCH') {
                $r->richInput = $record->updateFields;
            }
            $req[] = $r;
        }

        return json_encode(['batchRequests' => $req]);
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