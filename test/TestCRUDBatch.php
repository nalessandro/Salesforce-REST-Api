<?php

class TestCRUDBatch extends \PHPUnit\Framework\TestCase
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
        $this->crud = new SfRestApi\CRUD( new SfRestApi\Request\ClientConfig($params) );
    }

    public function tearDown()
    {
        $this->crud = null;
    }

    public function test_create_multiple_records_rest()
    {
        $records = array();
        for($i=0;$i<3;$i++)
        {
            $records[] = ['FirstName' => 'Name' . $i
                ,'LastName' => 'Last'
                ,'Phone' => '432543256' . $i
                ,'Email' => 'test' . $i . '@email.com'];
        }
        $response = $this->crud->insert('Contact', json_encode($records));
        var_dump($response);
    }

}