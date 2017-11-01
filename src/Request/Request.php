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

    public function query( String $q ) {
        $results = $this->send('GET'
            ,$this->getConfig()->getBaseUri().'/query?q='.str_replace(' ', '+', $q)
            ,'');
        return $results;
    }

    public static function _query( string $q ) {
        return self::$_instance->query($q);
    }


    public function insert (array $args) {
        $uri = $this->getConfig()->getBaseUri().'/sobjects/'.$args['object'];
        return $this->send('POST',$uri,$args['records']);
    }

    public function update (array $args) {
        $results = $this->send('PATCH'
            ,$this->getConfig()->getBaseUri().'/sobjects/'.$args['object'].'/'.$args['id']
            ,$args['records']);
        return $results;
    }

    public function delete (array $args) {
        $results = $this->send('DELETE'
            ,$this->getConfig()->getBaseUri().'/sobjects/'.$args['object'].'/'.$args['id']
            ,'');
        return $results;
    }


}