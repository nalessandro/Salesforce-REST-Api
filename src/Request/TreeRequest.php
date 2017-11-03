<?php

namespace SfRestApi\Request;

use SfRestApi\Contracts\RequestInterface;

/**
 * Class TreeRequest
 *
 * @package SfRestApi\Request
 */
class TreeRequest extends BaseRequest implements RequestInterface
{
    public static $_instance;

    public static function getInstance() {
        if( !isset( self::$_instance )) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function getConfig()
    {
        return parent::getConfig();
    }

    public function query (string $q) {

    }

    public function insert ()
    {
        // TODO: Implement insert() method.
    }
    public function update ()
    {
        // TODO: Implement update() method.
    }
    public function delete ()
    {
        // TODO: Implement delete() method.
    }
}