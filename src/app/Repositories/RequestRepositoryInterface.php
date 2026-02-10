<?php

namespace App\Repositories;

use App\Models\Request;
use Illuminate\Database\Eloquent\Collection;

interface RequestRepositoryInterface
{
    /**
     * 全件取得（実務では必要に応じてページネーションに変更）
     */
    public function all(): Collection;

    /**
     * 特定のIDで1件取得
     */
    public function findById(int $id): ?Request;

    /**
     * 申請の保存
     */
    public function store(array $data): Request;

    /**
     * 申請の更新
     */
    public function update(int $id, array $data): bool;

    public function updateStatus(int $id, array $attributes): bool;
}
