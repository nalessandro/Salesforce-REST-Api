<?php

namespace SfRestApi\Contracts;

/**
 * Interface ClientConfigInterface
 * @package SfRestApi\Interfaces
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
interface ClientConfigInterface
{
    /**
     * @return string
     */
    public function getBaseUrl();

    /**
     * @return string
     */
    public function getClientId();

    /**
     * @return string
     */
    public function getClientSecret();

    /**
     * @return string
     */
    public function getUsername();

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @return string
     */
    public function getSecurityToken();

    /**
     * @return string
     */
    public function getApiVersion();

    /**
     * @return string
     */
    public function getBaseUri();
}