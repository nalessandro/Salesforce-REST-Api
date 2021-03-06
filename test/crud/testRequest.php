<?php

namespace Test\crud;

class testRequest extends \PHPUnit\Framework\TestCase
{
    protected $crud;

    public function setUp() {
        $params = array('login_url' => 'https://na73.salesforce.com'
            ,'username' => \Test\Config::getUsername()
            ,'password' => \Test\Config::getPass()
            ,'client_id' => \Test\Config::getClientId()
            ,'client_secret' => \Test\Config::getClientSecret()
            ,'security_token' => \Test\Config::getSecurityToken() );
        $this->crud = new \SfRestApi\Client( json_encode( $params ));
    }

    public function tearDown() {
        $this->crud = null;
    }

    public function test_base_request_initialized() {
        $this->assertInstanceOf(\SfRestApi\Client::class, $this->crud);
        $this->assertInstanceOf(\SfRestApi\Request\ClientConfig::class, $this->crud->getConfig());
        $this->assertInstanceOf(\SfRestApi\Request\Request::class, $this->crud->getInstance());
    }

    public function test_query() {
        $q = 'SELECT Id, Name, Phone FROM Lead';
        $jsonResult = $this->crud->query($q);
        $records = json_decode($jsonResult);
        $this->assertGreaterThan(0,$records->totalSize);
    }

    public function test_create() {
        $records = json_encode(['FirstName' => 'Nathan'
            ,'LastName' => 'Alessandro'
            ,'Phone' => '7276674434'
            ,'Email' => 'nalessan@gmail.com'
        ]);
        $result = json_decode( $this->crud->insert(['object' => 'Contact', 'records' => $records]) );
        $this->assertTrue($result->success);
    }

    public function test_update() {
        $q = "SELECT Id FROM Contact WHERE Phone = '7276674434' and Email ='nalessan@gmail.com' and isDeleted = false LIMIT 1";
        $jsonResult = $this->crud->query($q);
        $response = json_decode($jsonResult);
        $records = json_encode([ 'Phone' => '1234567890']);
        $jsonResult = $this->crud->update(['object' => 'Contact', 'id' => $response->records[0]->Id,'records' => $records]);
        $this->assertEmpty($jsonResult);
    }

    public function test_delete() {
        $q = "SELECT Id FROM Contact WHERE Phone = '1234567890' and Email ='nalessan@gmail.com' and isDeleted = false";
        $jsonResult = $this->crud->query($q);
        $response = json_decode($jsonResult);
        $jsonResult = $this->crud->delete(['object' => 'Contact', 'id' => $response->records[0]->Id]);
        $this->assertEmpty($jsonResult);
    }
}