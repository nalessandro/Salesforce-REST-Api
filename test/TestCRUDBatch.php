<?php

namespace Test;

class TestCRUDBatch extends \PHPUnit\Framework\TestCase
{
    protected $crud;

    public function setUp() {
        $params = json_decode(json_encode(['login_url' => 'https://na73.salesforce.com'
            ,'username' => Config::getUsername()
            ,'password' => Config::getPass()
            ,'client_id' => Config::getClientId()
            ,'client_secret' => Config::getClientSecret()
            ,'security_token' => Config::getSecurityToken()]));
        $this->crud = new \SfRestApi\CRUD( new \SfRestApi\Request\ClientConfig($params) );
    }

    public function tearDown() {
        $this->crud = null;
    }

    public function test_create_multiple_records_rest() {
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