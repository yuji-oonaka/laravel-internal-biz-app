<?php

namespace App\Enums;

enum RequestStatus: string
{
    case DRAFT = 'draft';       // 下書き
    case PENDING = 'pending';   // 申請中
    case APPROVED = 'approved'; // 承認済み
    case REJECTED = 'rejected'; // 却下
    case RETURNED = 'returned'; // 差戻し
}
