<?php

namespace SfRestApi;

use SfRestApi\Request\ClientConfig;
use SfRestApi\Contracts\ClientConfigInterface;
use SfRestApi\Request\Request;
use SfRestApi\Request\BatchRequest;
use SfRestApi\Request\TreeRequest;

/**
 * Class CRUD
 * @package SfRestApi
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
class CRUD
{
    public function process(\stdClass $args ) {

        if( property_exists($args, 'type') )
            $this->$args['type']($args);
        else
            $this->single( $args );
    }

    protected function bulk( $args ) {

    }

    protected function batch( $args ) {

    }

    protected function composite( $args ) {

    }

    protected function single( $args ) {

    }

    /**
     * Query Method
     *
     * @param $query
     *
     * @return string
     */
    public function query($query): string {
        $results = $this->request->send('GET'
                        ,$this->request->getConfig()->getBaseUri().'/query?q='.str_replace(' ', '+', $query)
                        ,'');

        return $results;
    }

    /**
     * Insert Method
     *
     * @param array $args
     *
     * @return string
     */
    public function insert(array $args): string
    {
        if( count(json_decode($args['records'])) > 1 ) {
            $args['records'] = $this->request->prepBatch('insert',$args);
            $uri = $this->request->getConfig()->getBaseUri().'/composite/batch';
        }
        else {
            $uri = $this->request->getConfig()->getBaseUri().'/sobjects/'.$args['object'];
        }

        return $this->request->send('POST',$uri,$args['records']);
    }

    /**
     * Update Method
     *
     * @param array $args
     *
     * @return string
     */
    public function update(array $args): string
    {
        $results = $this->request->send('PATCH'
                        ,$this->request->getConfig()->getBaseUri().'/sobjects/'.$args['object'].'/'.$args['id']
                        ,$args['records']);

        return $results;
    }

    /**
     * Delete Method
     *
     * @param array $args
     *
     * @return string
     */
    public function delete(array $args): string
    {
        $results = $this->request->send('DELETE'
                    ,$this->request->getConfig()->getBaseUri().'/sobjects/'.$args['object'].'/'.$args['id']
                    ,'');

        return $results;
    }

    /**
     * SetBulk sets the CRUD methods to utilize the Salesforce Bulk Api
     */
    public function setBulk()
    {
        $this->isBulk = true;
    }

    /**
     * UnsetBulk sets the CRUD methods to use standard Salesforce REST Api
     */
    public function unsetBulk()
    {
        $this->isBulk = false;
    }

    public function getBulk(): boolean {
        return $this->isBulk;
    }
}