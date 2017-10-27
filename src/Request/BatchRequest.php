<?php

namespace SfRestApi\Request;

/**
 * Class BatchRequest
 *
 * @package SfRestApi\Request
 */
class BatchRequest extends Request
{
    /**
     * Insert Method
     *
     * @param array $args
     *
     * @return string
     */
    public function insert(array $args): string
    {
        $args['records'] = $this->prepBatch('POST', $args['records']);
        $uri = $this->getConfig()->getBaseUri().'/composite/tree/'.$args['object'];
        foreach( $args['records'] as $r ) {
            $result[] = $this->send('POST',$uri,$r);
        }
        return json_encode( $result );
    }

    /**
     * Update Method
     *
     * @param array $args
     *
     * @return string
     */
    public function update(array $args): string
    {
        if( count(json_decode($args['records'])) > 1) {

        }
        $results = $this->request->send('PATCH'
            ,$this->request->getConfig()->getBaseUri().'/sobjects/'.$args['object'].'/'.$args['id']
            ,$args['records']);

        return $results;
    }

    /**
     * Delete Method
     *
     * @param array $args
     *
     * @return string
     */
    public function delete(array $args): string
    {
        $results = $this->request->send('DELETE'
            ,$this->request->getConfig()->getBaseUri().'/sobjects/'.$args['object'].'/'.$args['id']
            ,'');

        return $results;
    }
}