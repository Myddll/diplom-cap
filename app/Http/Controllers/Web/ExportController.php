<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function export(): StreamedResponse
    {
        $orders = Order::query()->with('services')->get();

        $filename = 'orders_' . Carbon::now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ];

        return response()->streamDownload(function () use ($orders) {
            $output = fopen('php://output', 'w');

            fputcsv($output, [
                'order_id',
                'order_client_info',
                'order_client_tel',
                'order_client_address',
                'order_status',
                'order_order_date',
                'order_created_at',
                'order_updated_at',
                'services_id',
                'services_name',
                'services_description',
                'services_price',
                'services_quantity',
                'services_time',
            ]);

            foreach ($orders as $order) {
                foreach ($order->services as $service) {
                    fputcsv($output, [
                        $order->id,
                        $order->client_info,
                        $order->client_tel,
                        $order->client_address,
                        $order->status,
                        $order->order_date,
                        $order->created_at,
                        $order->updated_at,
                        $service->id,
                        $service->name,
                        $service->description,
                        $service->price,
                        $service->pivot->quantity,
                        $service->time,
                    ]);
                }
            }

            fclose($output);
        }, $filename, $headers);
    }
}
