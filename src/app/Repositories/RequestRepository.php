<?php

namespace App\Repositories;

use App\Models\Request;
use Illuminate\Database\Eloquent\Collection;

class RequestRepository implements RequestRepositoryInterface
{
    public function all(): Collection
    {
        return Request::with('user')->get();
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
}
