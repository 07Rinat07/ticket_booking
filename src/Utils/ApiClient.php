<?php

namespace App\Utils;

class ApiClient
{
    public function bookOrder(array $orderData): array
    {
        // Мокация ответа от API бронирования
        return (rand(0, 1) === 0)
            ? ['message' => 'order successfully booked']
            : ['error' => 'barcode already exists'];
    }

    public function approveOrder(string $barcode): array
    {
        // Мокация ответа от API подтверждения
        $responses = [
            ['message' => 'order successfully approved'],
            ['error' => 'event cancelled'],
            ['error' => 'no tickets'],
            ['error' => 'no seats'],
            ['error' => 'fan removed'],
        ];
        return $responses[array_rand($responses)];
    }
}
