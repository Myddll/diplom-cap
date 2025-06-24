<?php

namespace App\Http\Requests;

use App\Models\Order;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_info' => ['required', 'string', 'min:3', 'max:255'],
            'client_tel' => ['required', 'min:11', 'max:12', 'regex:/^(?:\+7|8)\d{10}$/'],
            'client_address' => ['required', 'string', 'min:3', 'max:255'],
            'order_date' => ['required', function ($attribute, $value, $fail) {
                return $this->checkTime($value, $fail);
            }],
            'order_comment' => ['nullable', 'string', 'max:255'],
            'services' => ['required', 'array', 'min:1', function ($attribute, $value, $fail) {
                return $this->checkServices($value, $fail);
            }],
        ];
    }

    private function checkTime(mixed $value, \Closure $fail)
    {
        $time = Carbon::parse($value);

        if ($time->isPast() || $time->isToday()) {
            return $fail('Неправильная дата');
        }
    }

    private function checkServices(mixed $value, \Closure $fail)
    {
        foreach ($value as $service) {
            $serviceModel = Service::query()->find($service['id']);

            if (!$serviceModel) {
                return $fail('Несуществующая услуга');
            }

            if (isset($service['quantity']) && $service['quantity'] < 1) {
                return $fail('Неккоретнное значение количества');
            }
        }
    }
}
