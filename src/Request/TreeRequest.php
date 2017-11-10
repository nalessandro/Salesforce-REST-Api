<?php

namespace SfRestApi\Request;

use SfRestApi\Contracts\CompositeInterface;

/**
 * Class TreeRequest
 * ------------------------------------------
 * Creates one or more sObject trees with root records of the specified type.
 * An sObject tree is a collection of nested, parent-child records with a single root record.
 *
 * In the request data, you supply the record hierarchies, required and optional field values,
 * each record’s type, and a reference ID for each record. Upon success, the response contains
 * the IDs of the created records. If an error occurs while creating a record, the entire request
 * fails. In this case, the response contains only the reference ID of the record that caused the
 * error and the error information.
 *
 * The request can contain the following:
 *      Up to a total of 200 records across all trees
 *      Up to five records of different types
 *      SObject trees up to five levels deep
 *
 * Because an sObject tree can contain a single record, you can use this resource to create up to
 * 200 unrelated records of the same type.
 * @link https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/resources_composite_sobject_tree.htm
 *
 * @package SfRestApi\Request
 */
class TreeRequest extends BaseRequest implements CompositeInterface
{
    /**
     * @var self()
     */
    public static $_instance;

    /**
     * @var string
     */
    protected $requestUri = '/composite/tree';

    public static function getInstance() {
        if( !isset( self::$_instance )) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Composite Tree Request
     * ------------------------------------------
     * Executes a series of REST API requests in a single call. You can use the output of one request
     * as the input to a subsequent request. The response bodies and HTTP statuses of the requests are
     * returned in a single response body. The entire request counts as a single call toward your API
     * limits.
     * @link https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/requests_composite.htm
     *
     * Sample JSON:
     * {
     *      "object" : "Account"
     *      ,"records" :[{
     *          "attributes" : {"type" : "Account", "referenceId" : "ref1"},
     *          "name" : "SampleAccount",
     *          "phone" : "1234567890",
     *          "website" : "www.salesforce.com",
     *          "numberOfEmployees" : "100",
     *          "industry" : "Banking",
     *          "Contacts" : {
     *              "records" : [{
     *                  "attributes" : {"type" : "Contact", "referenceId" : "ref2"},
     *                  "lastname" : "Smith",
     *                  "title" : "President",
     *                  "email" : "sample@salesforce.com"
     *              },{
     *                  "attributes" : {"type" : "Contact", "referenceId" : "ref3"},
     *                  "lastname" : "Evans",
     *                  "title" : "Vice President",
     *                  "email" : "sample@salesforce.com"
     *              }]
     *          }
     *      },{
     *          "attributes" : {"type" : "Account", "referenceId" : "ref4"},
     *          "name" : "SampleAccount2",
     *          "phone" : "1234567890",
     *          "website" : "www.salesforce2.com",
     *          "numberOfEmployees" : "100",
     *          "industry" : "Banking"
     *      }]
     * }
     *
     * @param  string $args    json formatted request body
     *
     * @return \stdClass       Salesforce response for each subrequest
     */
    public function request( string $args ): \stdClass {
        return $this->makeRequest( $args );
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
    protected function makeRequest( string $reqJson ): \stdClass {
        $args = json_decode( $reqJson );
        $object = null;
        if( array_key_exists('object', $args ) ) {
            $object = '/' . $args['object'];
            unset($args['object']);
        }

        $response = $this->send('POST'
            ,$this->getConfig()->getBaseUri().$this->requestUri.$object
            ,json_encode( $args )
        );
        return json_decode( $response );
    }
}