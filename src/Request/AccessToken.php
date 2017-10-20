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
    protected $refresh_token;

    /**
     * @var string
     */
    protected $token_expires;

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->access_token;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refresh_token;
    }

    /**
     * @return string
     */
    public function getTokenExpires(): string
    {
        return $this->token_expires;
    }
}