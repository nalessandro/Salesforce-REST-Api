<?php

namespace SfRestApi\Request;

use SfRestApi\Contracts\RequestInterface;

/**
 * Class BatchRequest
 *
 * Executes up to 25 subrequests in a single request. The response bodies
 * and HTTP statuses of the subrequests in the batch are returned in a
 * single response body. Each subrequest counts against rate limits.
 * https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/resources_composite_batch.htm
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
class BatchRequest extends BaseRequest implements RequestInterface
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
     * Batch Query
     *
     * @param string $q
     *
     * @return \stdClass
     */
    public function query ( string $q ): \stdClass {
        $requests = json_decode($q);
        foreach($requests as $query) {
            $r->method = 'GET';
            $r->url = $this->getConfig()->getBaseUri() . '/query?q=' . str_replace(' ', '+', $query);
            $ret[] = $r;
        }

        return $this->makeRequest( json_encode( ['batchRequests' => $ret ] ) );
    }

    /**
     * Insert Method
     *
     * Batch requests do not accept 'POST' sub-requests. This method
     * always throws an exception
     *
     * @param string $args
     *
     * @throws \Exception
     */
    public function insert (string $args) {
        throw new \Exception( 'Salesforce does not accept batch insert requests', 405 );
    }

    /**
     * Batch Update
     *
     * @param string $args
     *
     * @return \stdClass
     */
    public function update (string $args): \stdClass {
       return $this->makeRequest( $this->prepBatchRequest('PATCH', json_decode($args)) );
    }

    /**
     * Batch Delete
     *
     * @param string $args
     *
     * @return \stdClass
     */
    public function delete (string $args): \stdClass {
        return $this->makeRequest( $this->prepBatchRequest('DELETE', json_decode( $args )) );
    }

    /**
     * Mixed Requests (a true batch request)
     *
     * @param string $args
     */
    public function request (array $args) {

    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return string
     */
    protected function prepBatchRequest (string $method, \stdClass $args): string {

        foreach($args->batchRequests as $record) {
            $r->method = $method;
            $r->url = $this->getConfig()->getBaseUri() . '/sobjects/' . $args['object']
                    . (property_exists($record, 'id') ? '/' . $record->id : '');
            $r->body = $record;
            $ret[] = $r;
        }

        return json_encode(['batchRequests' => $ret]);
    }

    protected function makeRequest(string $reqJson): \stdClass {
        $response = $this->send('POST'
            ,$this->getConfig()->getBaseUri().$this->requestUri
            ,$reqJson
        );
        return json_decode( $response->getBody() );
    }
}