<?php

use PHPUnit\Framework\TestCase;

class TestRequest extends TestCase
{
    public function test_client_instantiation()
    {
        $client = new SfRestApi\Client('https://na73.salesforce.com'
                ,'nalessan@njafreelance.com'
                , 'Sal!24esForce'
                , '3MVG9g9rbsTkKnAWggzVdrX3.xMGLuDeHUtWUGa0dVA2M2I7anh6qfG50lUWZEYyoRh65ZcIRrC.GyxG37pzK'
                ,'6302695765669590000'
                , 'HlRqoQT4Ysv1dmumtDWtLZrtH');

        $client->search('Select Id FROM Lead LIMIT 10');
    }

}

?>