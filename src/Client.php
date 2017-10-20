<?php

namespace SfRestApi;

use SfRestApi\Request\ClientConfig;

/**
 * Class SalesforceClient
 * @package SfRestApi
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
class Client
{
    /**
     * @var Rest
     */
    protected $rest;

    /**
     * @var Bulk
     */
    protected $bulk;

    /**
     * Client constructor.
     *
     * @param String $login_url
     * @param String $username
     * @param String $password
     * @param String $client_id
     * @param String $client_secret
     * @param String $security_token
     */
    public function __construct(String $login_url
                                ,String $username
                                ,String $password
                                ,String $client_id
                                ,String $client_secret
                                ,String $security_token
    ) {
        $config = new ClientConfig($login_url, $username, $password, $client_id, $client_secret, $security_token);
        $this->rest = new Rest($config);
        $this->bulk = new Bulk($config);
    }

    public function search(String $query):\stdClass
    {
        $response = $this->rest->query($query);
        return new \stdClass();
    }
}