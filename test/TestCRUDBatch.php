<?php

namespace Test;

class TestCRUDBatch extends \PHPUnit\Framework\TestCase
{
    protected $crud;

    public function setUp()
    {
        $config = new Config();
        $params = json_decode(json_encode(['login_url' => 'https://na73.salesforce.com'
            ,'username' => $config->USERNAME
            ,'password' => $config->PASSWORD
            ,'client_id' => $config->CLIENT_ID
            ,'client_secret' => $config->CLIENT_SECRET
            ,'security_token' => $config->SECURITY_TOKEN]));
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