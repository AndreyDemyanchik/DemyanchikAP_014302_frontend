<?php

namespace App\Http\Controllers;

use App\Models\Visualization;

class MalfunctionController
{
    public function create(array $parameters)
    {
        Visualization::first()->update([
            'entity_title' => 'malfunctions',
            'fields' => json_encode(array_filter($parameters), JSON_UNESCAPED_UNICODE)
        ]);
    }
}
