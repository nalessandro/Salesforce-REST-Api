<?php

namespace SfRestApi\Request;

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
    protected $guzzle_client;

    /**
     * @var ClientConfigInterface
     */
    protected $config;

    /**
     * @var AccessToken
     * @access protected
     */
    protected $access_token;

    /**
     * BaseRequest constructor.
     * @param ClientConfigInterface $config
     */
    public function __construct( ClientConfigInterface $config )
    {
        $this->config = $config;
        $this->guzzle_client = new Client(['base_uri' => $config->getBaseUrl()]);
    }

    /**
     * @return string
     */
    protected function getAccessToken(): string
    {
        if (null === $this->access_token) {
            $post_data = [
                'grant_type'    => 'password',
                'client_id'     => $this->config->getClientId(),
                'client_secret' => $this->config->getClientSecret(),
                'username'      => $this->config->getUsername(),
                'password'      => $this->config->getPassword().$this->config->getSecurityToken(),
            ];

            $uri = sprintf('%s?%s', '/services/oauth2/token', http_build_query($post_data));
            $response = $this->guzzle_client->request('POST', $uri);

            if (200 == $response->getStatusCode()) {
                $body = json_decode($response->getBody(true), true);
                $this->access_token = new AccessToken( $body );
            }
        }

        return $this->access_token->getAccessToken();
    }

    /**
     * @return array
     */
    protected function getHeaders(): array
    {
        $headers = array(
            'content-type' => 'application/json',
            'accept' => 'application/json',
            'authorization' => sprintf('Bearer %s', $this->getAccessToken()),
            'x-prettyprint' => 1,
            'x-sfdc-session' => substr($this->access_token->getAccessToken(), strpos($this->access_token->getAccessToken(), '!'))
        );

        return $headers;
    }
}