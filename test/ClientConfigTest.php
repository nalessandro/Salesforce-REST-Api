<?php

class ClientConfigTest extends \PHPUnit\Framework\TestCase
{
    public function test_client_config_instantiation()
    {
        $params = json_decode(json_encode(['login_url' => 'a'
                    ,'username' => 'a'
                    ,'password' => 'a'
                    ,'client_id' => 'a'
                    ,'client_secret' => 'a'
                    ,'security_token' => 'a']));

        $config = new SfRestApi\Request\ClientConfig($params);
        $this->assertInstanceOf('SfRestApi\Request\ClientConfig', $config);
    }

    public function test_api_version_is_set_1()
    {
        $params = json_decode(json_encode(['login_url' => 'a'
            ,'username' => 'a'
            ,'password' => 'a'
            ,'client_id' => 'a'
            ,'client_secret' => 'a'
            ,'security_token' => 'a']));

        $config = new SfRestApi\Request\ClientConfig($params);
        $this->assertNotNull($config->getApiVersion());
    }

    public function test_api_version_is_set_2 ()
    {
        $params = json_decode(json_encode(['login_url' => 'a'
            ,'username' => 'a'
            ,'password' => 'a'
            ,'client_id' => 'a'
            ,'client_secret' => 'a'
            ,'security_token' => 'a'
            ,'version' => 'v39.0']));

        $config = new SfRestApi\Request\ClientConfig($params);
        $this->assertNotNull($config->getApiVersion());
    }


}