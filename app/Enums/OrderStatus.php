<?php

namespace App\Enums;

enum OrderStatus: string
{
    case New = 'new';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Canceled = 'canceled';
    case Completed = 'completed';

    /**
     * Получить лейбл для статуса заказа.
     */
    public function label(): string
    {
        return match ($this) {
            self::New => 'Новый',
            self::Processing => 'В процессе',
            self::Shipped => 'Отправлен',
            self::Canceled => 'Отменён',
            self::Completed => 'Выполнен',
        };
    }
}
