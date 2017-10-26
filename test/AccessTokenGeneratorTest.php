<?php

class AccessTokenGeneratorTest extends \PHPUnit\Framework\TestCase
{
    public function test_token_gets_generated_from_array()
    {
        $array = array('access_token' => '', 'token_type' => '');
        $token = new SfRestApi\Request\AccessToken($array);
        $this->assertInstanceOf('SfRestApi\Request\AccessToken', $token);
    }

}