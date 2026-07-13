<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FindRequest;
use App\Http\Requests\Api\Items\CreateUpdateItemRequest;

class ItemsController extends Controller
{
    function find(FindRequest $request)
    {
        $data = $request->validate();
    }

    function findOne() {}

    function createUpdate(CreateUpdateItemRequest $request)
    {
        $data = $request->validate();
    }

    function toggleActive() {}
}
