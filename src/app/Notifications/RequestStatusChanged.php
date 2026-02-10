<?php

namespace App\Notifications;

use App\Models\Request as WorkRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequestStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        protected WorkRequest $workRequest
    ) {}

    /**
     * 通知チャンネルの指定（今回はデータベース）
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * データベースに保存されるデータ形式
     */
    public function toArray(object $notifiable): array
    {
        return [
            'request_id' => $this->workRequest->id,
            'title'      => $this->workRequest->title,
            'status'     => $this->workRequest->status->label(), // Enumの日本語ラベル
            'message'    => "あなたの申請「{$this->workRequest->title}」が{$this->workRequest->status->label()}されました。",
        ];
    }
}
