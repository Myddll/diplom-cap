<?php

declare(strict_types=1);

namespace App\Repositories\Service;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function update(Order $order, array $data): bool
    {
//        $order = App\Models\Order::with('services')->find(1);
//        foreach ($order->services as $service) {
//            dd($service->pivot->quantity);
//        };

        return $order->update($data);
    }
}
