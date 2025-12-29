<?php

namespace App\Enums;

enum UserRole: string
{
    case AUTHOR = 'author';
    case MANAGER = 'manager';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match($this) {
            self::AUTHOR => 'Author',
            self::MANAGER => 'Manager',
            self::ADMIN => 'Admin',
        };
    }

    public function canApprove(): bool
    {
        return in_array($this, [self::MANAGER, self::ADMIN]);
    }

    public function canDelete(): bool
    {
        return $this === self::ADMIN;
    }
}
