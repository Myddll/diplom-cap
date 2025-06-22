<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firstTestModelService = Service::query()->create([
            'name' => 'Мытье окон',
            'description' => 'Мытье оконо - описание',
            'price' => 123.23,
            'time' => 100,
            'is_multiple' => true,
        ]);
        $secondTestModelService = Service::query()->create([
            'name' => 'Чистка квартиры',
            'description' => 'Чистка квартиры - описание',
            'price' => 123.23,
            'time' => 60,
        ]);
        $thirdTestModelService = Service::query()->create([
            'name' => 'Мытье посуды',
            'description' => 'Мытье посуды - описание',
            'price' => 123.23,
            'time' => 60,
        ]);

        $firstTestModelOrder = Order::query()->create([
            'client_info' => 'Иванов Иван Иванович',
            'client_tel' => '81234567890',
            'client_address' => 'ул. Пушкина д. 228 кв. 5',
            'order_comment' => 'Я на ковер насрал',
            'order_date' => Carbon::parse('25.06.2025 18:00 +3')->toDateTime(),
        ]);

        $firstTestModelOrder->services()->sync([
            $firstTestModelService->id => [
                 'quantity' => 8,
            ],
            $secondTestModelService->id,
            $thirdTestModelService->id,
        ]);

        $secondTestModelOrder = Order::query()->create([
            'client_info' => 'Петрошин Владислав Сергеевич',
            'client_tel' => '890123456789',
            'client_address' => 'ул. Пушкина д. 228 кв. 567',
            'order_date' => Carbon::parse('28.04.2025 10:00 +3')->toDateTime(),
        ]);

        $secondTestModelOrder->services()->sync([$secondTestModelService->id, $thirdTestModelService->id]);
    }
}
