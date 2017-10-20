<?php

namespace SfRestApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SfRestApiService
{
    /**
     * @var mixed
     * @access protected
     */
    protected $jobId;

    /**
     * @return array|bool|float|int|string
     */
    public function getVersions()
    {
        $uri = '/services/data/';

        return $this->client->request('GET', $uri);
    }

    /**
     * @return array of Resources
     */
    public function getAvailableResources()
    {
        $uri = sprintf('/services/data/%s', $this->apiVersion);
        $response = $this->client->request('GET', $uri, [ 'headers' => $this->getHeaders()]);

        return json_decode($response->getBody(true), true);
    }

    /**
     * Perform a SOQL query on Salesforce
     *
     *
     * @param string $query     The Query string you would like executed on Salesforce.
     * @return bool|mixed       Return False on exception otherwise returns array of records
     */
    public function query(string $query)
    {
        $uri = str_replace(' ', '+', sprintf('%s%s', $this->baseUri.'/'.$this->apiVersion.'/query/?q=', $query));

        try
        {
            $result = $this->client->request('GET', $uri, [ 'headers' => $this->getHeaders() ]);
        }
        catch (GuzzleException $e)
        {
            throw new \Exception( $e->getResponse()->getBody()->getContents() );
        }

        return json_decode($result->getBody()->getContents());
    }

    /**
     * Query additional data over and above the maximum 2000 records returned
     * in a Salesforce REST query.
     *
     * @param  string $uri [description]
     * @return obj      [description]
     */
    public function queryMore(string $uri)
    {
        try
        {
            $result = $this->client->request('GET', $uri, ['headers' => $this->getHeaders() ]);
        }
        catch (GuzzleException $e)
        {
            throw new \Exception( $e->getResponse()->getBody()->getContents() );
        }

        return json_decode($result->getBody()->getContents());
    }

    /**
     * TODO: Build Out?
     * Function to update a single record of an object
     *
     * @param string $sobject   The object to be updated
     * @param array $record     The record data with which to update
     */
    public function update(string $sobject, array $record)
    {

    }

    /**
     * TODO: Build out?
     * Function to insert a single record of an object
     *
     * @param string $sobject   The object to be updated
     * @param array $record     The record data with which to insert
     */
    public function insert(string $sobject, array $record)
    {

    }

    /**
     * Helper function to insert a batch of records through the bulk api
     *
     * @param string $sobject   The object to be updated
     * @param array $record     The data with which to update
     */
    public function bulkInsert( string $sobject, array $records )
    {
        return $this->sendNewBatch( $sobject, 'insert', json_encode( $records ) );
    }

    /**
     * Helper function to update a batch of records through the bulk api
     *
     * @param string $sobject   The object to be updated
     * @param array $record     The data with which to update
     */
    public function bulkUpdate( string $sobject, array $records )
    {
        return $this->sendNewBatch( $sobject, 'update', json_encode( $records ) );
    }

    /**
     * Send new batch of data to Salesoforce Bulk API for processing
     *
     * @param string $sobject   Object on which the bulk job will run
     * @param string $type      Type of process to be run {update|insert|etc.}
     * @param string $records   JSON string of records to process
     * @return bool
     */
    protected function sendNewBatch( string $sobject, string $type, string $records )
    {
        $this->getJob( $sobject, $type );

        $uri = '/services/async/' . str_replace('v', '', $this->apiVersion) . '/job/' . $this->jobId . '/batch';

        try
        {
            $result = $this->client->request('POST',
                $uri,
                [
                    'headers' => $this->getHeaders(),
                    'body' => $records
                ]
            );
        }
        catch (GuzzleException $e)
        {
            throw new \Exception( $e->getResponse()->getBody()->getContents() );
        }

        $this->closeJob();
        $this->jobId = '';

        return $result;
    }

    /**
     * Retrieve the Salesforce Job Id
     *
     * @param string $sobject   The Object in Salesforce on which to create the job
     * @param string $type      The type of job you are performing {update|insert}
     * @return bool|mixed       Return string $this->jobId or false
     */
    protected function getJob(string $sobject, string $type)
    {
        if(!$this->jobId)
        {
            return $this->createJob(strtolower($sobject), $type);;
        }

        return true;
    }

    /**
     * Create new job instance on Salesforce.
     *
     * @param string $obj       The Object the job will run on
     * @param string $jobType   The process that will occur {insert|update|etc.}
     * @return bool
     */
    protected function createJob(string $obj, string $jobType)
    {
        $uri = '/services/async/' . str_replace('v', '', $this->apiVersion) . '/job';

        $headers = $this->getHeaders();
        $headers['operation'] = $jobType;
        $headers['object'] = $obj;

        $body = array(
            'operation' => $jobType,
            'object' => $obj,
            'contentType' => 'JSON'
        );

        try {
            $result = $this->client->request('POST',
                $uri,
                [
                    'headers' => $headers,
                    'body' => json_encode($body)
                ]
            );
        }
        catch (GuzzleException $e)
        {
            throw new \Exception( $e->getResponse()->getBody()->getContents() );
        }

        $job = json_decode($result->getBody()->getContents());
        $this->jobId = $job->id;

        return true;
    }

    /**
     * Close Salesforce Job created for batch processing
     *
     * @return boolean
     */
    protected function closeJob()
    {
        $uri = $uri = '/services/async/' . str_replace('v', '', $this->apiVersion) . '/job/' . $this->jobId;

        try
        {
            $response = $this->client->request('POST',
                $uri,
                [
                    'headers' => $this->getHeaders(),
                    'body' => json_encode(array('state' => 'Closed'))
                ]
            );
        }
        catch (GuzzleException $e)
        {
            throw new \Exception( $e->getResponse()->getBody()->getContents() );
        }

        return true;
    }


}