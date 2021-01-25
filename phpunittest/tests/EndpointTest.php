<?php 

require 'vendor/autoload.php';
use PHPUnit\Framework\TestCase;

class EndpointTest extends TestCase {

    public function test_endpoint()
    {

        $client = new GuzzleHttp\Client();
        $response = $client->request('POST', 'http://localhost:8009/convert', [
                'base' => 'USD',
                'symbol' => 'EUR',
                'amount' => 12
        ]);
        $this->assertEquals(200, $response->getStatusCode());

    }
}
?> 