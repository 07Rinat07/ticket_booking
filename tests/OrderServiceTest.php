<?php

use PHPUnit\Framework\TestCase;
use App\Services\OrderService;
use App\Repositories\OrderRepository;
use App\Utils\ApiClient;
use App\Services\BarcodeGenerator;
use Mockery;

class OrderServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testCreateOrder()
    {
        $orderRepository = Mockery::mock(OrderRepository::class);
        $apiClient = Mockery::mock(ApiClient::class);
        $barcodeGenerator = Mockery::mock(BarcodeGenerator::class);
        
        $orderData = [
            'event_id' => 3,
            'event_date' => '2024-12-01 18:00:00',
            'ticket_adult_price' => 1000,
            'ticket_adult_quantity' => 2,
            'ticket_kid_price' => 500,
            'ticket_kid_quantity' => 1,
            'user_id' => 45,
        ];

        $barcodeGenerator->shouldReceive('generate')
                         ->andReturn('unique_barcode_123');

        $orderRepository->shouldReceive('save')
                        ->once();

        $apiClient->shouldReceive('bookOrder')
                  ->andReturn(['message' => 'order successfully booked']);
        $apiClient->shouldReceive('approveOrder')
                  ->andReturn(['message' => 'order successfully approved']);

        $orderService = new OrderService($orderRepository, $apiClient, $barcodeGenerator);
        $orderService->createOrder($orderData);

        $this->assertTrue(true); // Проверка прошла без ошибок
    }

    public function testBarcodeGenerationOnConflict()
    {
        $orderRepository = Mockery::mock(OrderRepository::class);
        $apiClient = Mockery::mock(ApiClient::class);
        $barcodeGenerator = Mockery::mock(BarcodeGenerator::class);

        $barcodeGenerator->shouldReceive('generate')
                         ->andReturn('duplicate_barcode', 'unique_barcode_456');

        $apiClient->shouldReceive('bookOrder')
                  ->twice()
                  ->andReturn(['error' => 'barcode already exists'], ['message' => 'order successfully booked']);

        $orderService = new OrderService($orderRepository, $apiClient, $barcodeGenerator);
        $orderData = [
            'event_id' => 3,
            'event_date' => '2024-12-01 18:00:00',
            'ticket_adult_price' => 1000,
            'ticket_adult_quantity' => 2,
            'ticket_kid_price' => 500,
            'ticket_kid_quantity' => 1,
            'user_id' => 45,
        ];

        $orderService->createOrder($orderData);

        $this->assertTrue(true);
    }
}
