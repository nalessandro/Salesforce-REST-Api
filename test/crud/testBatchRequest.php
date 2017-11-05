<?php

namespace Test\crud;

use PHPUnit\Framework\TestCase;

class testBatchRequest extends TestCase
{
    protected $crud;

    public function setUp() {
        $params = array('login_url' => 'https://na73.salesforce.com'
        ,'username' => \Test\Config::getUsername()
        ,'password' => \Test\Config::getPass()
        ,'client_id' => \Test\Config::getClientId()
        ,'client_secret' => \Test\Config::getClientSecret()
        ,'security_token' => \Test\Config::getSecurityToken() );
        $this->crud = new \SfRestApi\Client( json_encode( $params ), 'Batch');
    }

    public function tearDown() {
        $this->crud = null;
    }

    public function test_base_request_initialized() {
        $this->assertInstanceOf(\SfRestApi\Client::class, $this->crud);
        $this->assertInstanceOf(\SfRestApi\Request\ClientConfig::class, $this->crud->getConfig());
        $this->assertInstanceOf(\SfRestApi\Request\BatchRequest::class, $this->crud->getInstance());
    }

    public function test_query() {
        $q[] = ['query' => 'SELECT Id, Name, Phone FROM Lead'];
        $q[] = ['query' => 'SELECT Id, Name, Phone FROM Contact'];
        $response = $this->crud->query( $q  );

        $this->assertFalse($response->hasErrors);
    }

    public function test_create() {
        $records = array(['FirstName' => 'Nathan','LastName' => 'Alessandro','Phone' => '7276674434','Email' => 'nalessan@gmail.com']
            ,['FirstName' => 'Nathan','LastName' => 'D\'Alessandro','Phone' => '7891234567','Email' => 'nalessan1@gmail.com']
        );
        try {
            $result = $this->crud->insert(['object' => 'Contact','batchRequests' => $records] );
        }
        catch (\Exception $e){
            $this->assertEquals(405, $e->getCode());
        }
    }

    public function test_update() {
        $q[]['query'] = 'SELECT Id, LastName FROM Contact LIMIT 2';
        $response = $this->crud->query( $q );
        $records = $response->results[0]->result->records;
        foreach($records as $r) {
            $r->object = $r->attributes->type;
            $r->updateFields = ['LastName' => $r->LastName];
            $upd['records'][] = $r;
        }
        $upd['object'] = 'Contact';
        $jsonResult = $this->crud->update( $upd );
        $this->assertFalse( $jsonResult->hasErrors );
    }

    public function test_delete() {
        $q[]['query'] = 'SELECT Id FROM Contact WHERE Phone = \'1234567890\'';
        $response = $this->crud->query( $q );
        $records = $response->results[0]->result->records;
        foreach($records as $r) {
            $r->object = $r->attributes->type;
            $del['records'][] = $r;
        }
        $del['object'] = 'Contact';
        $jsonResult = $this->crud->delete( $del );
        $this->assertFalse( $jsonResult->hasErrors );
    }
}