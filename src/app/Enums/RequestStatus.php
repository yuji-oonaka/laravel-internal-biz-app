<?php

namespace App\Enums;

enum RequestStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    /**
     * 日本語ラベルを返す
     */
    public function label(): string
    {
        return match ($this) {
            self::DRAFT    => '下書き',
            self::PENDING  => '申請中',
            self::APPROVED => '承認済み',
            self::REJECTED => '却下',
        };
    }
}
