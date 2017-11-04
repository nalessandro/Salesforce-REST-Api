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
 * @todo THROW ERROR ON INSERT
 *
 * @package SfRestApi\Request
 */
class BatchRequest extends BaseRequest implements RequestInterface
{
    public static $_instance;

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
    public function query (string $q): \stdClass {
        $requests = json_decode($q, true);
        foreach($requests as $query) {
            $r['method'] = 'GET';
            $r['url'] = $this->getConfig()->getBaseUri() . '/query?q=' . str_replace(' ', '+', $query);
            $ret[] = $r;
        }
        $results = $this->send('POST'
            ,$this->getConfig()->getBaseUri().'/composite/batch'
            ,json_encode(['batchRequests' => $ret]));

        return json_decode( $results );
    }

    /**
     * Insert Method
     *
     * Batch requests do not accept 'POST' sub-requests. This method
     * always throws an exception
     *
     * @param array $args
     *
     * @throws \Exception
     */
    public function insert (array $args) {
        throw new \Exception( 'Salesforce does not accept batch insert requests', 405 );
    }

    /**
     * Batch Update
     *
     * @param array $args
     *
     * @return \stdClass
     */
    public function update (array $args): \stdClass {
        $reqJson = $this->prepBatchRequest('PATCH', $args);
        $response = $this->send('POST'
            ,$this->getConfig()->getBaseUri() . '/composite/batch'
            ,$reqJson
        );

        return json_decode($response->getBody());
    }

    /**
     * Batch Delete
     *
     * @param array $args
     *
     * @return \stdClass
     */
    public function delete (array $args): \stdClass {
        $reqJson = $this->prepBatchRequest('DELETE', $args);
        $response = $this->send('POST'
            ,$this->getConfig()->getBaseUri() . '/composite/batch'
            ,$reqJson
        );

        return json_decode($response->getBody());
    }

    /**
     * Mixed Requests (a true batch request)
     * @param array $args
     */
    public function request (array $args) {

    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return string
     */
    protected function prepBatchRequest (string $method, array $args): string {

        foreach($args['batchRequests'] as $record) {
            $r['method'] = $method;
            $r['url'] = $this->getConfig()->getBaseUri() . '/sobjects/' . $args['object']
                    . (array_key_exists('id', $record) ? '/' . $record['id'] : '');
            $r['body'] = $record;
            $ret[] = $r;
        }

        return json_encode(['batchRequests' => $ret]);
    }
}