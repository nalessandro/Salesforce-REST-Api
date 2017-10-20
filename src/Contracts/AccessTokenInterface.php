<?php

namespace SfRestApi\Contracts;

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
     * @param string
     */
    public function setAccessToken();
    /**
     * @return string
     */
    public function getRefreshToken();

    /**
     * @param string
     */
    public function setRefreshToken();
}