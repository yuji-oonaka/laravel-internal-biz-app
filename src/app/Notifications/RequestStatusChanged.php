<?php

namespace App\Notifications;

use App\Models\Request as WorkRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage; // 追加

class RequestStatusChanged extends Notification
{
    use Queueable;

    public function __construct(protected WorkRequest $workRequest) {}

    /**
     * 通知チャンネルの指定
     */
    public function via(object $notifiable): array
    {
        // 'database' だけでなく 'mail' も追加！
        return ['database', 'mail'];
    }

    /**
     * メール通知の内容を定義
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = $this->workRequest->status->label();

        return (new MailMessage)
            ->subject("【重要】申請が{$statusLabel}されました")
            ->greeting("{$notifiable->name} 様")
            ->line("あなたの申請「{$this->workRequest->title}」のステータスが更新されました。")
            ->line("現在のステータス：**{$statusLabel}**")
            ->action('申請詳細を確認する', url(route('requests.show', $this->workRequest->id)))
            ->line('ご確認のほど、よろしくお願いいたします。');
    }

    /**
     * データベース保存用の内容
     */
    public function toArray(object $notifiable): array
    {
        return [
            'request_id' => $this->workRequest->id,
            'title'      => $this->workRequest->title,
            'status'     => $this->workRequest->status->label(),
        ];
    }
}
