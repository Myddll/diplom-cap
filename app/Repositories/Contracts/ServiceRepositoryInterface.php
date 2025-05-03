<?php

namespace App\Repositories\Contracts;

use App\Models\Service;

interface ServiceRepositoryInterface
{
    public function create(array $data): Service;
    public function update(Service $service, array $data): bool;
}
