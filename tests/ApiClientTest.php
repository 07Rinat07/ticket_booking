<?php

use PHPUnit\Framework\TestCase;
use App\Utils\ApiClient;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;

class ApiClientTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testBookOrderSuccess()
    {
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('post')
               ->andReturn(new Response(200, [], json_encode(['message' => 'order successfully booked'])));

        $apiClient = new ApiClient($client);
        $response = $apiClient->bookOrder([...]);

        $this->assertEquals('order successfully booked', $response['message']);
    }

    public function testBookOrderBarcodeConflict()
    {
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('post')
               ->andReturn(new Response(200, [], json_encode(['error' => 'barcode already exists'])), 
                           new Response(200, [], json_encode(['message' => 'order successfully booked'])));

        $apiClient = new ApiClient($client);
        $response = $apiClient->bookOrder([...]);

        $this->assertEquals('order successfully booked', $response['message']);
    }

    public function testApproveOrderSuccess()
    {
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('post')
               ->andReturn(new Response(200, [], json_encode(['message' => 'order successfully approved'])));

        $apiClient = new ApiClient($client);
        $response = $apiClient->approveOrder('unique_barcode');

        $this->assertEquals('order successfully approved', $response['message']);
    }
}
