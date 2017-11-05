<?php

namespace SfRestApi\Request;

use SfRestApi\Contracts\RequestInterface;

/**
 * Class BatchRequest
 *
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
    public function batch ( string $args ): \stdClass {
        $req = json_decode( $args );
        return $this->makeRequest(json_encode( ['batchRequests' => $req] ));
    }

    /**
     * Batch Query
     * ------------------------------------------
     * Generates a batch request where all subrequests are query calls
     *
     * Required Properties:
     *      query: The query to perform
     *
     * @param string $q
     *
     * @return \stdClass
     */
    public function query ( string $q ): \stdClass {
        $requests = json_decode($q);
        $r = new \stdClass();
        for($i=0;$i<count($requests);$i++) {
            $r->method = 'GET';
            $r->url = $this->getConfig()->getBaseUri() . '/query?q=' . str_replace(' ', '+',
                    urlencode($requests[$i]->query));
            $req[] = $r;
        }

        return $this->makeRequest( json_encode( ['batchRequests' => $req ] ) );
    }

    /**
     * Batch Insert Method
     * ------------------------------------------
     * Batch requests do not accept 'POST' sub-requests. This method
     * always throws an exception
     *
     * @param string $args
     *
     * @return \stdClass
     * @throws \Exception
     */
    public function insert (string $args): \stdClass {
        throw new \Exception( 'Salesforce does not accept batch insert requests', 405 );
    }

    /**
     * Batch Update Method
     * ------------------------------------------
     * Generates a single batch request with multiple subrequests from
     * an array or object. each record must meet all Required Properties.
     * All subrequests are update calls
     *
     * Required Properties:
     *      records:
     *      object:         The object for each of
     *      id:             The record to update
     *      updateFields:   Fields to update
     *
     * @param string $args
     *
     * @return \stdClass
     */
    public function update (string $args): \stdClass {
        //$args = json_decode($args);
       return $this->makeRequest( $this->prepRequest('PATCH', json_decode( $args ) ));
    }

    /**
     * Batch Update Method
     * ------------------------------------------
     * Generates a single batch request with multiple subrequests from
     * an array or object. each record must meet all Required Properties.
     * All subrequests are delete calls
     *
     * Required Properties:
     *      object: The object for each of
     *      records[]:
     *          id: Id of the record to update
     *          updateFields: Fields to update
     *
     * @param string $args
     *
     * @return \stdClass
     */
    public function delete (string $args): \stdClass {
        return $this->makeRequest( $this->prepRequest('DELETE', json_decode( $args )) );
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