<?php

namespace SfRestApi\Request;

use SfRestApi\Contracts\RequestInterface;

/**
 * Class Request
 *
 * @todo Add QueryAll
 *
 * @package SfRestApi\Config
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
class Request extends BaseRequest implements RequestInterface
{
    /**
     * @var Request
     */
    public static $_instance;

    /**
     * @var string
     */
    protected $requestUri = '/sobjects/';

    /**
     * Return an instance of Request
     * @return Request
     */
    public static function getInstance() {
        if( !isset( self::$_instance )) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Query Method
     * ------------------------------------------
     * Preforms a single query
     * @link https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/dome_query.htm
     *
     * Required Properties:
     *      Query
     *
     * @todo: ADD QUERY MORE ABILITY TO RETURN MORE THAN THE MAX 200
     *
     * @param string        $q  JSON encoded object. The query you would like to perform
     * @return \stdClass        Results as an Object
     */
    public function query (string $q): \stdClass {
        $results = $this->send('GET'
            ,$this->getConfig()->getBaseUri().'/query?q='.str_replace(' ', '+', $q)
            ,'');
        return $results;
    }

    /**
     * Create Record
     * ------------------------------------------
     * Creates a single record
     * @link https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/dome_sobject_create.htm
     *
     * @see Salesforce documentation for required fields when creating sobject records
     * Required Properties:
     *      object: The object of the record (Lead, Contact, Account, etc)
     *      record: The record fields to insert
     *
     * @param string        $args
     * @return \stdClass
     */
    public function insert (string $args): \stdClass {
        $args = json_decode( $args );
        $uri = $this->getConfig()->getBaseUri() . $this->requestUri . $args->object;
        return $this->send('POST',$uri,$args->record);
    }

    /**
     * Update Record
     * ------------------------------------------
     * Updates (upserts) a single record
     * @link https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/dome_update_fields.htm
     *
     * Required Properties:
     *      object: The object of the record (Lead, Contact, Account, etc)
     *      id:     The Id of the record being updated
     *      record: The Fields to update on the record
     *
     * @param string $args
     * @return \stdClass
     */
    public function update (string $args): \stdClass {
        $results = $this->send('PATCH'
            ,$this->getConfig()->getBaseUri() . $this->requestUri . $args->object.'/'.$args->id
            ,$args->record);
        return $results;
    }

    /**
     * Delete Record
     * ------------------------------------------
     * Deletes a single record
     * @link https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/dome_delete_record.htm
     *
     * Required Properties:
     *      object: The object of the record (Lead, Contact, Account, etc)
     *      id:     The Id of the record being deleted
     *
     * @param string $args
     * @return \stdClass
     */
    public function delete (string $args): \stdClass {
        $results = $this->send('DELETE'
            ,$this->getConfig()->getBaseUri() . $this->requestUri . $args->object.'/'.$args->id
            ,'');
        return $results;
    }


}