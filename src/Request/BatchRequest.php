<?php

namespace SfRestApi\Request;

use SfRestApi\Contracts\RequestInterface;

/**
 * Class BatchRequest
 *
 * @todo ADD ERROR HANDLING
 * @todo HANDLE MORE THAN 25 RESULTS
 *
 * @package SfRestApi\Request
 */
class BatchRequest extends BaseRequest implements RequestInterface
{
    public static $_instance;

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
        return json_decode($results->getBody());
    }

    public function insert (array $args): \stdClass {
        $reqJson = $this->prepCompositeRequest('POST', $args);
        $response = $this->send('POST'
            ,$this->getConfig()->getBaseUri() . '/composite'
            ,$reqJson
        );

        return json_decode($response->getBody());
    }

    public function update (array $args): \stdClass {
        $reqJson = $this->prepCompositeRequest('PATCH', $args);
        $response = $this->send('POST'
            ,$this->getConfig()->getBaseUri() . '/composite'
            ,$reqJson
        );

        return json_decode($response->getBody());
    }

    public function delete (array $args): \stdClass {
        $reqJson = $this->prepCompositeRequest('DELETE', $args);
        $response = $this->send('POST'
            ,$this->getConfig()->getBaseUri() . '/composite'
            ,$reqJson
        );

        return json_decode($response->getBody());
    }

    /**
     * Insert Method
     *
     * @param array $args
     *
     * @return string

    public function insert(array $args): string
    {
        $args['records'] = $this->prepBatch('POST', $args['records']);
        $uri = $this->getConfig()->getBaseUri().'/composite/tree/'.$args['object'];
        foreach( $args['records'] as $r ) {
            $result[] = $this->send('POST',$uri,$r);
        }
        return json_encode( $result );
    } */

    /**
     * Update Method
     *
     * @param array $args
     *
     * @return string

    public function update(array $args): string
    {
        if( count(json_decode($args['records'])) > 1) {

        }
        $results = $this->request->send('PATCH'
            ,$this->request->getConfig()->getBaseUri().'/sobjects/'.$args['object'].'/'.$args['id']
            ,$args['records']);

        return $results;
    } */

    /**
     * Delete Method
     *
     * @param array $args
     *
     * @return string

    public function delete(array $args): string
    {
        $results = $this->request->send('DELETE'
            ,$this->request->getConfig()->getBaseUri().'/sobjects/'.$args['object'].'/'.$args['id']
            ,'');

        return $results;
    }*/

    /**
     * @param string $method
     * @param array  $args
     *
     * @return string
     */
    protected function prepCompositeRequest (string $method, array $args): string {
        $ret = array(); $i=0;
        foreach($args['compositeRequest'] as $record) {
            $r['method'] = $method;
            $r['url'] = $this->getConfig()->getBaseUri() . '/sobjects/' . $args['object'];
            $r['referenceId'] = $args['object'].$i;
            $r['body'] = $record;
            $ret[] = $r;
            $i++;
        }

        return json_encode(['compositeRequest' => $ret]);
    }
}