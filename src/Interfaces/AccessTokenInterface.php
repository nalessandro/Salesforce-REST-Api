<?php

namespace SfRestApi\Interfaces;

/**
 * Interface AccessTokenInterface
 * @package SfRestApi\Interfaces
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
interface AccessTokenInterface
{
    /**
     * @return string
     */
    public function getAccessToken();

    /**
     * @return string
     */
    public function getRefreshToken();
}