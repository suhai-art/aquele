<?php

namespace App\Actions\Client;

use App\Models\Client;

class CreateUpdateClientAction
{
    public function execute(array $data, ?string $id = null): Client
    {
        $client = $id !== null
            ? Client::query()->findOrFail($id)
            : new Client();

        $client->fill($data);
        $client->save();

        return $client;
    }
}
