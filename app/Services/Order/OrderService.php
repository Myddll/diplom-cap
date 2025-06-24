<?php

declare(strict_types=1);

namespace App\Services\Order;

use App\Models\Order;
use App\Models\Service;

class OrderService
{
    public function addOrder(array $orderData): void
    {
        $newOrder = Order::query()->create([
            'client_info' => $orderData['client_info'],
            'client_tel' => $orderData['client_tel'],
            'client_address' => $orderData['client_address'],
            'order_date' => $orderData['order_date'],
            'order_comment' => $orderData['order_comment'],
            'status' => Order::CREATED_STATUS_CODE,
        ]);

        foreach ($orderData['services'] as $service) {
            $serviceModel = Service::query()->find($service['id']);

            if ($serviceModel->is_multiple) {
                $newOrder->services()->attach($serviceModel->id, [
                    'quantity' => $service['quantity'],
                ]);
            } else {
                $newOrder->services()->attach($serviceModel->id);
            }
        }
    }
}
