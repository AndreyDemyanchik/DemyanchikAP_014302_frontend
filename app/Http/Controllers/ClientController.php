<?php

namespace App\Http\Controllers;

use App\Models\Visualization;

class ClientController extends Controller
{
    public function create(array $parameters)
    {
        Visualization::first()->update([
            'entity_title' => 'clients',
            'fields' => json_encode(array_filter($parameters), JSON_UNESCAPED_UNICODE)
        ]);
    }
}
