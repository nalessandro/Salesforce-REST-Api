<?php
/**
 * SalesForce Authentication
 *
 * @author: Nathan Alessandro
 * @email: nalessan@gmail.com
 *
 * CHANGE LOG:
 * 10/20/17 - NJA - Created
 */

namespace SfRestApi\Config;

use GuzzleHttp\Client;

class Authentication
{
    /**
     * @var Client
     */
    protected $client;

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
     * RESTApi constructor.
     *
     * @param string $consumerKey
     * @param string $consumerSecret
     * @param string $username
     * @param string $password
     * @param string $securityToken
     * @param string $baseUrl
     */
    public function __construct(
        $consumerKey,
        $consumerSecret,
        $username,
        $password,
        $securityToken,
        $baseUrl
    ){
        $this->isAuthorized   = false;
        $this->consumerKey    = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        $this->username       = $username;
        $this->password       = $password;
        $this->securityToken  = $securityToken;
        $this->baseUrl        = $baseUrl;

        $this->baseUri = '/services/data';

        $this->client = new Client(['base_uri' => $baseUrl]);
    }

    /**
     * @return mixed
     */
    protected function getAccessToken()
    {
        if (null === $this->accessToken) {
            $query = [
                'grant_type'    => 'password',
                'client_id'     => $this->consumerKey,
                'client_secret' => $this->consumerSecret,
                'username'      => $this->username,
                'password'      => $this->password.$this->securityToken,
            ];

            $uri = sprintf('%s?%s', '/services/oauth2/token', http_build_query($query));
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