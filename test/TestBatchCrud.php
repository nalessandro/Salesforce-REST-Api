<?php

namespace Test;

class TestBatchCrud extends \PHPUnit\Framework\TestCase
{
    protected $crud;

    public function setUp() {
        $params = json_decode(json_encode(['login_url' => 'https://na73.salesforce.com'
            ,'username' => Config::getUsername()
            ,'password' => Config::getPass()
            ,'client_id' => Config::getClientId()
            ,'client_secret' => Config::getClientSecret()
            ,'security_token' => Config::getSecurityToken()]));
        $this->crud = new \SfRestApi\Client( json_encode($params) );
    }

    public function tearDown() {
        $this->crud = null;
    }

    public function test_query() {
        $q = 'SELECT Id, Name, Phone FROM Lead';
        $jsonResult = $this->crud->query($q);
        $records = json_decode($jsonResult);
        $this->assertGreaterThan(0,$records->totalSize);
    }

    public function test_create() {
        $records = json_encode(['FirstName' => 'Nathan','LastName' => 'Alessandro','Phone' => '7276674434','Email' => 'nalessan@gmail.com']
                                ,['FirstName' => 'Nathan','LastName' => 'D\'Alessandro','Phone' => '7891234567','Email' => 'nalessan1@gmail.com']
                            );
        $result = json_decode( $this->crud->insert(['object' => 'Contact','records' => $records]) );
        $this->assertTrue($result->success);
    }

    public function test_update() {
        $q = "SELECT Id 
              FROM Contact 
              WHERE Phone IN ('7276674434','7891234567') 
                and Email IN ('nalessan@gmail.com','nalessan1@gmail.com') and isDeleted = false LIMIT 1";
        $jsonResult = $this->crud->query($q);
        $response = json_decode($jsonResult);
        $records = json_encode([ 'Phone' => '1234567890']);
        $jsonResult = $this->crud->update(['object' => 'Contact',$response->records[0]->Id,$records );
        $this->assertEmpty($jsonResult);
    }

    public function test_delete() {
        $q = "SELECT Id FROM Contact WHERE Phone = '1234567890' and Email ='nalessan@gmail.com' and isDeleted = false";
        $jsonResult = $this->crud->query($q);
        $response = json_decode($jsonResult);
        $jsonResult = $this->crud->delete('Contact', $response->records[0]->Id);
        $this->assertEmpty($jsonResult);
    }
}