<?php

namespace App\Repositories;

use App\Models\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RequestRepository implements RequestRepositoryInterface
{
    public function all(array $filters = []): LengthAwarePaginator
    {
        $query = Request::with('user');

        // ステータスで絞り込み
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // ユーザーIDで絞り込み（自分の申請のみ表示用など）
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        // 最新順で、1ページ10件取得
        return $query->latest()->paginate(10);
    }

    public function findById(int $id): ?Request
    {
        return Request::find($id);
    }

    public function store(array $data): Request
    {
        return Request::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $request = $this->findById($id);
        if (!$request) {
            return false;
        }
        return $request->update($data);
    }

    public function updateStatus(int $id, array $attributes): bool
    {
        $request = $this->findById($id);
        if (!$request) {
            return false;
        }
        return $request->update($attributes);
    }
}
