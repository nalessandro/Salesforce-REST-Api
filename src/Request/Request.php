<?php

namespace SfRestApi\Request;

use SfRestApi\Contracts\ClientConfigInterface;

/**
 * Class Request
 * @package SfRestApi\Config
 * @author Nathan Alessandro <nalessan@gmail.com>
 */
class Request extends BaseRequest
{
    /**
     * Request constructor.
     * @param ClientConfigInterface $config
     */
    public function __construct(ClientConfigInterface $config)
    {
        parent::__construct($config);
    }

    /**
     * Make Request to Salesforce
     *
     * @param String $method
     * @param string $uri
     * @param String $body
     * @return string
     * @throws \Exception
     */
    public function send(String $method, string $uri, String $body): string
    {
        $response = $this->guzzle_client->request($method
                                        ,$uri
                                        ,['headers' => $this->getHeaders()
                                            ,'body' => $body
                                            ,'http_errors' => false]
                                        );
        //var_dump($response);
        if(strpos($response->getStatusCode(), 2) == 0) {
            return $response->getBody();
        }

        throw new \Exception('{error: ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase() .'}' );
    }

    /**
     * Preps multiple records to be sent in a Batch
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
            $r['method'] = $method,
            $r['url'] = $this->getConfig()->getApiVersion() . "/sobjects/" . $args['object'] . '/' . $r['id'],
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