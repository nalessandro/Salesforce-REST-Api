<?php

namespace Test\crud;

class testCompositeRequest extends \PHPUnit\Framework\TestCase
{
    protected $crud;

    public function setUp() {
        $params = array('login_url' => 'https://na73.salesforce.com'
        ,'username' => \Test\Config::getUsername()
        ,'password' => \Test\Config::getPass()
        ,'client_id' => \Test\Config::getClientId()
        ,'client_secret' => \Test\Config::getClientSecret()
        ,'security_token' => \Test\Config::getSecurityToken() );
        $this->crud = new \SfRestApi\Client( json_encode( $params ), 'Composite');
    }

    public function tearDown() {
        $this->crud = null;
    }

    public function test_composite_request_initialized() {
        $this->assertInstanceOf(\SfRestApi\Client::class, $this->crud);
        $this->assertInstanceOf(\SfRestApi\Request\ClientConfig::class, $this->crud->getConfig());
        $this->assertInstanceOf(\SfRestApi\Request\CompositeRequest::class, $this->crud->getInstance());
    }
}