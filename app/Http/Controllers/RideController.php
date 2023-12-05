<?php

namespace App\Http\Controllers;

use App\Models\Visualization;

class RideController
{
    public function create(array $parameters)
    {
        Visualization::first()->update([
            'entity_title' => 'rides',
            'fields' => json_encode(array_filter($parameters), JSON_UNESCAPED_UNICODE)
        ]);
    }
}
