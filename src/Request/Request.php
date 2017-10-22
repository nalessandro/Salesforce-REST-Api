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
        if(strpos($response->getStatusCode(), 2) == 0)
        {
            return $response->getBody();
        }

        throw new \Exception('{error: ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase() .'}' );
    }

    /**
     * Preps multiple records to be sent in a Batch
     *
     * @param String $object
     * @param String $records
     * @return string
     */
    public function prepBatch(String $object, String $records)
    {
        $recs = json_decode($records, true);
        $i=0;
        foreach($recs as $r)
        {
             $r['attributes'] = ['type' => $object, 'referenceId' => $object.$i];
             $recs[$i] = $r;
             $i++;
        }
        $records_batch_formatted['records'] = $recs;

        return json_encode($records_batch_formatted);
    }

}