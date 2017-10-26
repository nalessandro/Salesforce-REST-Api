<?php

class TestCrud extends \PHPUnit\Framework\TestCase
{
    protected $crud;

    public function setUp()
    {
        $params = json_decode(json_encode(['login_url' => 'https://na73.salesforce.com'
            ,'username' => 'nalessan@njafreelance.com'
            ,'password' => 'Sal!24esForce'
            ,'client_id' => '3MVG9g9rbsTkKnAWggzVdrX3.xMGLuDeHUtWUGa0dVA2M2I7anh6qfG50lUWZEYyoRh65ZcIRrC.GyxG37pzK'
            ,'client_secret' => '6302695765669590000'
            ,'security_token' => 'HlRqoQT4Ysv1dmumtDWtLZrtH']));
        $this->crud = new \SfRestApi\CRUD( new SfRestApi\Request\ClientConfig($params) );
    }

    public function tearDown()
    {
        $this->crud = null;
    }


    public function test_query()
    {
        $q = 'SELECT Id, Name, Phone FROM Lead';
        $jsonResult = $this->crud->query($q);
        $records = json_decode($jsonResult);
        $this->assertGreaterThan(0,$records->totalSize);
    }

    public function test_create()
    {
        $records = json_encode(['FirstName' => 'Nathan'
                            ,'LastName' => 'Alessandro'
                            ,'Phone' => '7276674434'
                            ,'Email' => 'nalessan@gmail.com'
                ]);

        $result = json_decode( $this->crud->insert('Contact', $records) );
        $this->assertTrue($result->success);
    }

    public function test_update()
    {
        $q = "SELECT Id FROM Contact WHERE Phone = '7276674434' and Email ='nalessan@gmail.com' and isDeleted = false LIMIT 1";
        $jsonResult = $this->crud->query($q);
        $response = json_decode($jsonResult);
        $records = json_encode([ 'Phone' => '1234567890']);
        $jsonResult = $this->crud->update('Contact',$response->records[0]->Id,$records );
        $this->assertEmpty($jsonResult);
    }

    public function test_delete()
    {
        $q = "SELECT Id FROM Contact WHERE Phone = '1234567890' and Email ='nalessan@gmail.com' and isDeleted = false";
        $jsonResult = $this->crud->query($q);
        $response = json_decode($jsonResult);
        $jsonResult = $this->crud->delete('Contact', $response->records[0]->Id);
        $this->assertEmpty($jsonResult);
    }



}