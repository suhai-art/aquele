<?php

namespace App\Actions\Item;

use App\Models\Item;

class DeleteItemAction
{
    public function execute(string $id): void
    {
        $item = Item::query()->findOrFail($id);

        $item->delete();
    }
}
