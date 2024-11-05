<?php

namespace App\Repositories;

use App\Models\Order;
use PDO;

class OrderRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Order $order): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO orders (event_id, event_date, ticket_adult_price, ticket_adult_quantity, ticket_kid_price, ticket_kid_quantity, barcode, user_id, equal_price, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $order->event_id,
            $order->event_date,
            $order->ticket_adult_price,
            $order->ticket_adult_quantity,
            $order->ticket_kid_price,
            $order->ticket_kid_quantity,
            $order->barcode,
            $order->user_id,
            $order->equal_price,
            $order->created,
        ]);
    }
}
