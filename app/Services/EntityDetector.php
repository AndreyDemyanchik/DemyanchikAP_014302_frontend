<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Visualization;

class EntityDetector
{
    /**
     * @return mixed
     */
    public static function process(): mixed
    {
        $visualization = Visualization::first();
        $visualization->fields = json_decode($visualization->fields, true);

        return $visualization;
    }
}
