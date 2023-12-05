<?php

namespace App\Http\Controllers;

use App\Models\Visualization;
use Illuminate\Http\Request;

class ScooterController extends Controller
{
    public function create(array $parameters)
    {
        Visualization::first()->update([
            'entity_title' => 'scooters',
            'fields' => json_encode(array_filter($parameters), JSON_UNESCAPED_UNICODE)
        ]);
    }
}
