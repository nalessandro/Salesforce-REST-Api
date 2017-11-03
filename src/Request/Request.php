<?php

namespace SfRestApi\Request;

use SfRestApi\Contracts\RequestInterface;

/**
 * Class Request
 *
 * @package SfRestApi\Config
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
class Request extends BaseRequest implements RequestInterface
{
    /**
     * @var Request
     */
    public static $_instance;

    /**
     * Return an instance of Request
     * @return Request
     */
    public static function getInstance() {
        if( !isset( self::$_instance )) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function query (string $q): \stdClass {
        $results = $this->send('GET'
            ,$this->getConfig()->getBaseUri().'/query?q='.str_replace(' ', '+', $q)
            ,'');
        return $results;
    }

    public function insert (array $args): \stdClass {
        $uri = $this->getConfig()->getBaseUri().'/sobjects/'.$args['object'];
        return $this->send('POST',$uri,$args['records']);
    }

    public function update (array $args): \stdClass {
        $results = $this->send('PATCH'
            ,$this->getConfig()->getBaseUri().'/sobjects/'.$args['object'].'/'.$args['id']
            ,$args['records']);
        return $results;
    }

    public function delete (array $args): \stdClass {
        $results = $this->send('DELETE'
            ,$this->getConfig()->getBaseUri().'/sobjects/'.$args['object'].'/'.$args['id']
            ,'');
        return $results;
    }


}