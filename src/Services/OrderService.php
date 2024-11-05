<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Utils\ApiClient;

class OrderService
{
    private OrderRepository $orderRepository;
    private ApiClient $apiClient;
    private BarcodeGenerator $barcodeGenerator;

    public function __construct(OrderRepository $orderRepository, ApiClient $apiClient, BarcodeGenerator $barcodeGenerator)
    {
        $this->orderRepository = $orderRepository;
        $this->apiClient = $apiClient;
        $this->barcodeGenerator = $barcodeGenerator;
    }

    public function createOrder(array $data): bool
    {
        // Расчёт общей стоимости заказа
        $equal_price = ($data['ticket_adult_price'] * $data['ticket_adult_quantity']) + ($data['ticket_kid_price'] * $data['ticket_kid_quantity']);
        $data['equal_price'] = $equal_price;

        // Генерация уникального баркода и резервирование заказа
        do {
            $data['barcode'] = $this->barcodeGenerator->generate();
            $response = $this->apiClient->bookOrder($data);
        } while (isset($response['error']) && $response['error'] === 'barcode already exists');

        // Проверка статуса бронирования
        if (!isset($response['message'])) {
            return false;
        }

        // Подтверждение заказа
        $confirmResponse = $this->apiClient->approveOrder($data['barcode']);
        if (!isset($confirmResponse['message'])) {
            return false;
        }

        // Сохранение заказа в БД
        $order = new Order($data);
        return $this->orderRepository->save($order);
    }
}
