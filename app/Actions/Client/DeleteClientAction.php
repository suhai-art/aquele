<?php

namespace App\Actions\Client;

use App\Models\Client;

class DeleteClientAction
{
    public function execute(string $id): void
    {
        $client = Client::query()->findOrFail($id);

        $client->delete();
    }
}
