<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\User;
use App\Enums\UserRole;

class RequestPolicy
{
    /**
     * 一覧画面の閲覧認可
     */
    public function viewAny(User $user): bool
    {
        // ログインしていれば誰でも一覧ページ自体にはアクセス可能とする
        return true;
    }

    /**
     * 個別申請の詳細閲覧認可
     */
    public function view(User $user, Request $request): bool
    {
        // 管理者なら全閲覧可能、一般社員なら自分の申請のみ
        return $user->role === UserRole::ADMIN || $user->id === $request->user_id;
    }

    /**
     * 申請の更新（編集）認可
     */
    public function update(User $user, Request $request): bool
    {
        // 一般社員は、自分の申請かつ「下書き」状態の時のみ編集可能とする
        // (管理者は承認・却下はするが、内容の直接編集はさせない設計)
        return $user->id === $request->user_id && $request->status->name === 'DRAFT';
    }
}
