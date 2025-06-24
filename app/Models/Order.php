<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $status
 */

class Order extends Model
{
    public const STATUS_CODES = [
        self::CREATED_STATUS_CODE,
        self::IN_WORK_STATUS_CODE,
        self::COMPLETE_STATUS_CODE,
        self::CANCELED_STATUS_CODE,
    ];

    public const CREATED_STATUS_CODE = 0;
    public const IN_WORK_STATUS_CODE = 1;
    public const COMPLETE_STATUS_CODE = 2;
    public const CANCELED_STATUS_CODE = 3;

    use SoftDeletes;

    protected $fillable = [
        'client_info',
        'client_tel',
        'client_address',
        'order_comment',
        'order_date',
        'status',
    ];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)->withPivot('quantity');
    }

    public function getStatusText(): string
    {
        return match ($this->status) {
            self::CREATED_STATUS_CODE => __('Создано'),
            self::IN_WORK_STATUS_CODE => __('В работе'),
            self::COMPLETE_STATUS_CODE => __('Выполнено'),
            self::CANCELED_STATUS_CODE => __('Закрыто'),
            default => __('Неизвестный статус'),
        };
    }
}
