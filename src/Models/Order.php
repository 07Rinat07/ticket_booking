<?php

namespace App\Models;

class Order
{
    public int $id;
    public int $event_id;
    public string $event_date;
    public int $ticket_adult_price;
    public int $ticket_adult_quantity;
    public int $ticket_kid_price;
    public int $ticket_kid_quantity;
    public string $barcode;
    public int $user_id;
    public int $equal_price;
    public string $created;

    public function __construct(array $data)
    {
        $this->event_id = $data['event_id'];
        $this->event_date = $data['event_date'];
        $this->ticket_adult_price = $data['ticket_adult_price'];
        $this->ticket_adult_quantity = $data['ticket_adult_quantity'];
        $this->ticket_kid_price = $data['ticket_kid_price'];
        $this->ticket_kid_quantity = $data['ticket_kid_quantity'];
        $this->user_id = $data['user_id'];
        $this->barcode = $data['barcode'];
        $this->equal_price = $data['equal_price'];
        $this->created = date('Y-m-d H:i:s');
    }
}
