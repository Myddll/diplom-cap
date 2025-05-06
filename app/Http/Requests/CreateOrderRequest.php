<?php

namespace App\Http\Requests;

use App\Models\Order;
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
            'time' => ['required', function ($attribute, $value, $fail) {
                return $this->checkTime($value, $fail);
            }],
        ];
    }

    private function checkTime(mixed $value, \Closure $fail)
    {
        $time = Carbon::parse($value);
        $orders = Order::query()->whereBetween('time', [$time, $time->addMinutes(30)])->get();

        if ($orders->isNotEmpty()) {
            return $fail('Выбранна занятая дата');
        }
    }
}
