<?php

namespace SfRestApi\Request;

use GuzzleHttp\Client;
use AccessToken;
use SfRestApi\Contracts\ClientConfigInterface;

/**
 * Class BaseRequest
 * @package SfRestApi\Request
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
class BaseRequest
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ClientConfigInterface
     */
    protected $config;

    /**
     * @var mixed
     * @access protected
     */
    protected $apiVersion = 'v41.0';

    /**
     * @var mixed
     * @access protected
     */
    protected $consumerKey;

    /**
     * @var mixed
     * @access protected
     */
    protected $isAuthorized;

    /**
     * @var mixed
     * @access protected
     */
    protected $accessToken;

    /**
     * @var mixed
     * @access protected
     */
    protected $consumerSecret;

    /**
     * @var string
     * @access protected
     */
    protected $username;

    /**
     * @var mixed
     * @access protected
     */
    protected $password;

    /**
     * @var mixed
     * @access protected
     */
    protected $securityToken;

    /**
     * @var string
     * @access protected
     */
    protected $baseUrl;

    /**
     * @var string
     * @access protected
     */
    protected $baseUri;

    /**
     * BaseRequest constructor.
     * @param ClientConfigInterface $config
     */
    public function __construct(ClientConfigInterface $config){
        $this->isAuthorized   = false;
        $this->config = $config;
        /*$this->consumerKey    = $config->getClientId();
        $this->consumerSecret = $config->getClientSecret();
        $this->username       = $config->getUsername();
        $this->password       = $config->getPassword();
        $this->securityToken  = $config->getSecurityToken();
        $this->baseUrl        = $config->getLoginUrl();*/

        $this->baseUri = '/services/data'.$config->getApiVersion();

        $this->client = new Client(['base_uri' => $config->baseUrl]);
    }

    /**
     * @return mixed
     */
    protected function getAccessToken()
    {
        if (null === $this->accessToken) {
            $post_data = [
                'grant_type'    => 'password',
                'client_id'     => $this->consumerKey,
                'client_secret' => $this->consumerSecret,
                'username'      => $this->username,
                'password'      => $this->password.$this->securityToken,
            ];

            $uri = sprintf('%s?%s', '/services/oauth2/token', http_build_query($post_data));
            $response = $this->client->request('POST', $uri);

            if (200 == $response->getStatusCode()) {
                $body = json_decode($response->getBody(true), true);
                $this->accessToken = $body['access_token'];
                $this->isAuthorized = true;
            }
        }

        return $this->accessToken;
    }

    /**
     * @return array
     */
    protected function getHeaders()
    {
        $headers = array(
            'content-type' => 'application/json',
            'accept' => 'application/json',
            'authorization' => sprintf('Bearer %s', $this->getAccessToken()),
            'x-prettyprint' => 1,
            'x-sfdc-session' => substr($this->getAccessToken(), strpos($this->getAccessToken(), '!'))
        );

        return $headers;
    }
}