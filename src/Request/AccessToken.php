<?php

namespace SfRestApi\Request;

use SfRestApi\Contracts\AccessTokenInterface;

/**
 * Class AccessToken
 * @package SfRestApi\Request
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
class AccessToken implements AccessTokenInterface
{
    /**
     * @var string
     */
    protected $access_token;

    /**
     * @var string
     */
    protected $token_type;

    /**
     * AccessToken constructor.
     * @param array $token
     */
    public function __construct(array $token)
    {
        $this->access_token = $token['access_token'];
        $this->token_type = $token['token_type'];
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->access_token;
    }

    /**
     * @param string $access_token
     */
    public function setAccessToken(string $access_token)
    {
        $this->access_token = $access_token;
    }

    /**
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->token_type;
    }