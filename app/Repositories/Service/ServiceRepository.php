<?php

declare(strict_types=1);

namespace App\Repositories\Service;

use App\Models\Service;
use App\Repositories\Contracts\ServiceRepositoryInterface;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function create(array $data): Service
    {
        return Service::create($data);
    }

    public function update(Service $service, array $data): bool
    {
        return $service->update($data);
    }
}
