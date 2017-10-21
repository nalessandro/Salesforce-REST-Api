<?php

namespace SfRestApi;

use SfRestApi\Request\Request;
use SfRestApi\Contracts\ClientConfigInterface;

/**
 * Class CRUD
 * @package SfRestApi
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
class CRUD
{
    protected $request;

    protected $isBulk = false;

    public function __construct(ClientConfigInterface $config)
    {
        $this->request = new Request($config);
    }

    /**
     * @param String $query
     * @return string
     */
    public function query(String $query): string
    {
        $results = $this->request->send('GET'
                        ,$this->request->getConfig()->getBaseUri().'/query?q='.str_replace(' ', '+', $query)
                        ,'');

        return $results;
    }

    /**
     * Insert Method
     *
     * @param String $object
     * @param String $records
     * @return string
     */
    public function insert(String $object, String $records): string
    {
        $results = $this->request->send('POST'
                        ,$this->request->getConfig()->getBaseUri().'/sobjects/'.$object
                        ,$records);

        return $results;
    }

    /**
     * Update Method
     *
     * @param String $object
     * @param String $records
     * @return string
     */
    public function update(String $object, String $records): string
    {
        $results = $this->request->send('POST'
                        ,$this->request->getConfig()->getBaseUri().'/sobjects/'.$object
                        ,$records);

        return $results;
    }

    /**
     * Delete Method
     *
     * @param String $object
     * @param String $records
     * @return string
     */
    public function delete(String $object, String $records): string
    {
        $results = $this->request->send('POST'
                    ,$this->request->getConfig()->getBaseUri().'/sobjects/'.$object
                    ,$records);

        return $results;
    }

    public function setBulk()
    {
        $this->isBulk = true;
    }

    public function unsetBulk()
    {
        $this->isBulk = false;
    }
}