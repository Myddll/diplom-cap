<?php

declare(strict_types=1);

namespace App\Dto\Order;

use Carbon\Carbon;

class OrderDto
{
    public function __construct(
        public ?int $id,
        public string $clientInfo,
        public string $clientTel,
        public string $clientAddress,
        public int $status,
        public Carbon $orderDate,
        public Carbon $createDate,
        public Carbon $updateDate,
    ) {
    }
}
