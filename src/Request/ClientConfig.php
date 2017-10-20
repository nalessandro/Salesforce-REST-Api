<?php

namespace SfRestApi\Request;

use SfRestApi\Contracts\ClientConfigInterface;

/**
 * Class ClientConfig
 * @package SfRestApi
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
class ClientConfig implements ClientConfigInterface
{
    /**
     * @var string
     */
    private $client_id;

    /**
     * @var string
     */
    private $client_secret;

    /**
     * @var string
     */
    private $login_url;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $security_token;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $base_uri = '/services/data';


    public function __construct(String $login_url
                                ,String $username
                                ,String $password
                                ,String $client_id
                                ,String $client_secret
                                ,string $security_token
                                ,String $version = 'v41.0'
    ) {
        $this->login_url = $login_url;
        $this->username = $username;
        $this->password = $password;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->security_token = $security_token;
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->client_id;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->client_secret;
    }

    /**
     * @return string
     */
    public function getLoginUrl(): string
    {
        return $this->login_url;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getSecurityToken(): string
    {
        return $this->security_token;
    }

    /**
     * @return string
     */
    public function getApiVersion(): string
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->base_uri;
    }
}