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
    private $base_url;

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


    public function __construct(\stdClass $params)
    {
        $this->validateParams($params);
        $this->base_url = $params->login_url;
        $this->username = $params->username;
        $this->password = $params->password;
        $this->client_id = $params->client_id;
        $this->client_secret = $params->client_secret;
        $this->security_token = $params->security_token;
        $this->version = property_exists($params, 'version') ? $params->version : 'v41.0';
        $this->base_uri .= '/'.$this->version;
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
    public function getBaseUrl(): string
    {
        return $this->base_url;
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

    protected function validateParams(\stdClass $params) {
        foreach($params as $k => $v) {
            if($v == null || $v = '') {
                throw new \Exception($v . ' is a required parameter value');
            }
        }
    }
}