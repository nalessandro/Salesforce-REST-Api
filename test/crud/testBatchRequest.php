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
        $q[] = 'SELECT Id, Name, Phone FROM Lead';
        $q[] = 'SELECT Id, Name, Phone FROM Contact';
        $response = $this->crud->query( json_encode($q) );

        $this->assertFalse($response->hasErrors);
    }

    public function test_create() {
        $records = array(['FirstName' => 'Nathan','LastName' => 'Alessandro','Phone' => '7276674434','Email' => 'nalessan@gmail.com']
            ,['FirstName' => 'Nathan','LastName' => 'D\'Alessandro','Phone' => '7891234567','Email' => 'nalessan1@gmail.com']
        );
        try {
            $result = json_decode( $this->crud->insert(['object' => 'Contact','batchRequests' => $records]) );
        }
        catch (\Exception $e){
            $this->assertEquals(405, $e->getCode());
        }
    }

    /*public function test_update() {
        $q = "SELECT Id
              FROM Contact
              WHERE Phone IN ('7276674434','7891234567')
                and Email IN ('nalessan@gmail.com','nalessan1@gmail.com') and isDeleted = false LIMIT 1";
        $jsonResult = $this->crud->query($q);
        $response = json_decode($jsonResult);
        $records = json_encode([ 'Phone' => '1234567890']);
        $jsonResult = $this->crud->update(['object' => 'Contact',$response->records[0]->Id,$records] );
        $this->assertEmpty($jsonResult);
    }

    /*public function test_delete() {
        $q = "SELECT Id FROM Contact WHERE Phone = '1234567890' and Email ='nalessan@gmail.com' and isDeleted = false";
        $jsonResult = $this->crud->query($q);
        $response = json_decode($jsonResult);
        $jsonResult = $this->crud->delete('Contact', $response->records[0]->Id);
        $this->assertEmpty($jsonResult);
    }*/
}