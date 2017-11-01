<?php

namespace SfRestApi\Request;

use SfRestApi\Contracts\RequestInterface;

/**
 * Class CompositeRequest
 *
 * @package SfRestApi\Request
 */
class CompositeRequest extends BaseRequest implements RequestInterface
{
    /**
     * Preps multiple records to be sent in a Batch
     *
     * @todo Refactor for composite requests
     *
     * @param String $method
     * @param array  $args
     *
     * @return array
     */
    public function prepBatch(String $method, array $args) {

        $i=0;
        $batched = array();
        $r1 = array();
        foreach($args as $r) {
            $r['method'] = $method;
            $r['url'] = $this->getConfig()->getApiVersion() . "/sobjects/" . $args['object'] . '/' . $r['id'];
            $r['richInput'] = $r;
            $r1[] = $r;
            $i++;

            if($i%200 == 0) {
                $batched[] = $r1;
                $r1 = array();
            }
        }

        return $batched;
    }
}