<?php

namespace App\Repositories\Contracts;

use App\Models\Order;

interface OrderRepositoryInterface
{
    public function create(array $data): Order;
    public function update(Order $order, array $data): bool;
}
