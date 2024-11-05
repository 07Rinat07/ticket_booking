<?php

namespace App\Services;

class BarcodeGenerator
{
    public function generate(): string
    {
        return (string)rand(10000000, 99999999);
    }
}
