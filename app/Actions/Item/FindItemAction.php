<?php

namespace App\Actions\Item;

use App\Models\Item;
use Illuminate\Pagination\LengthAwarePaginator;

class FindItemAction
{
    public function findOne(string $id): Item
    {
        return Item::query()->findOrFail($id);
    }

    public function find(int $page, string $search, int $perPage): LengthAwarePaginator
    {
        $query = Item::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('internal_code', 'like', "%{$search}%");
            });
        }

        return $query->paginate(
            perPage: $perPage,
            page: $page
        );
    }
}
