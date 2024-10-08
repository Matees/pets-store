<?php

namespace App\Api\Enums;

enum PetStatus: string
{
    case AVAILABLE = 'available';
    case PENDING = 'pending';
    case SOLD = 'sold';

    /**
     * Check if a given status is valid.
     *
     * @param string $status
     * @return bool
     */
    public static function isValid(string $status): bool
    {
        return in_array($status, array_column(self::cases(), 'value'), true);
    }

    public static function getStatuses(): string
    {
        return implode(', ', array_column(self::cases(), 'value'));
    }
}

