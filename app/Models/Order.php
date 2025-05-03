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

    private const CREATED_STATUS_CODE = 0;
    private const IN_WORK_STATUS_CODE = 1;
    private const COMPLETE_STATUS_CODE = 2;
    private const CANCELED_STATUS_CODE = 3;

    use SoftDeletes;

    protected $fillable = [
        'client_info',
        'client_tel',
        'client_address',
    ];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)->withPivot(['quantity']);
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
