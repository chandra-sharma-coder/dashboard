<?php

namespace App\Enums;

enum PostAction: string
{
    case CREATED = 'created';
    case UPDATED = 'updated';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case DELETED = 'deleted';

    public function label(): string
    {
        return match($this) {
            self::CREATED => 'Post Created',
            self::UPDATED => 'Post Updated',
            self::APPROVED => 'Post Approved',
            self::REJECTED => 'Post Rejected',
            self::DELETED => 'Post Deleted',
        };
    }
}
