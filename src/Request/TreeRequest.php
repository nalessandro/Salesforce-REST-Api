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

    public function tree ( string $args ): \stdClass {

    }

    public function query (string $q): \stdClass {

    }

    public function insert (string $args): \stdClass {
        // TODO: Implement insert() method.
    }

    public function update (string $args): \stdClass {
        // TODO: Implement update() method.
    }

    public function delete (string $args): \stdClass {
        // TODO: Implement delete() method.
    }
}