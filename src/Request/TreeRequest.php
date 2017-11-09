<?php

namespace SfRestApi\Request;

use SfRestApi\Contracts\RequestInterface;

/**
 * Class TreeRequest
 *
 * @package SfRestApi\Request
 */
class TreeRequest extends BaseRequest implements CompositeInterface
{
    public static $_instance;

    public static function getInstance() {
        if( !isset( self::$_instance )) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function request ( string $args ): \stdClass {

    }
}