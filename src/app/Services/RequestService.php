<?php

namespace App\Services;

use App\Repositories\RequestRepositoryInterface;
use App\Models\Request;
use Illuminate\Database\Eloquent\Collection;

class RequestService
{
    // Repository Interface をインジェクション
    public function __construct(
        protected RequestRepositoryInterface $requestRepository
    ) {}

    /**
     * 申請一覧の取得ロジック
     */
    public function getAllRequests(): Collection
    {
        // 修正前: return $this->requestRepository::all();
        // 修正後: アロー演算子を使用
        return $this->requestRepository->all();
    }
    /**
     * 申請作成ロジック
     */
    public function createRequest(int $userId, array $data): Request
    {
        $data['user_id'] = $userId;
        // ステータスはマイグレーションで 'draft' がデフォルトだが、
        // ビジネスルールとしてここで明示的に設定する場合もある
        return $this->requestRepository->store($data);
    }
}
