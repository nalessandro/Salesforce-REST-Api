<?php

namespace SfRestApi\Request;

use AccessToken;
use GuzzleHttp\Client;
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
    protected $guzzleclient;

    /**
     * @var ClientConfigInterface
     */
    protected $config;

    /**
     * @var AccessToken
     * @access protected
     */
    protected $accessToken;

    /**
     * @var mixed
     * @access protected
     */
    protected $isAuthorized;

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
        $this->accessToken = new AccessToken();
        $this->config = $config;

        $this->baseUri = '/services/data'.$config->getApiVersion();

        $this->guzzleclient = new Client(['base_uri' => $config->baseUrl]);
    }

    /**
     * @return mixed
     */
    protected function getAccessToken()
    {
        if (null === $this->accessToken->getAccessToken()) {
            $post_data = [
                'grant_type'    => 'password',
                'client_id'     => $this->config->getClientId(),
                'client_secret' => $this->config->getClientSecret(),
                'username'      => $this->config->getUsername(),
                'password'      => $this->config->getPassword().$this->config->getSecurityToken(),
            ];

            $uri = sprintf('%s?%s', '/services/oauth2/token', http_build_query($post_data));
            $response = $this->guzzleclient->request('POST', $uri);

            if (200 == $response->getStatusCode()) {
                $body = json_decode($response->getBody(true), true);
                $this->accessToken->setAccessToken($body['access_token']);
                $this->isAuthorized = true;
            }
        }

        return $this->accessToken->getAccessToken();
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