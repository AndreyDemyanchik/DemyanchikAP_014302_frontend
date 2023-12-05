<?php

declare(strict_types=1);

namespace App\Services\VisualizationAggregators;

use App\Services\EntityDetector;

class AggregatorsFactory
{
    /**
     * @var mixed
     */
    protected mixed $entity;

    public function __construct()
    {
        $this->entity = EntityDetector::process();
    }

    /**
     * @return array
     */
    public function process(): array
    {
        return match ($this->entity->entity_title)
        {
            'clients' => (new Client())->aggregate(),
            'scooters' => (new Scooter())->aggregate(),
            'malfunctions' => (new Malfunction())->aggregate(),
            'rides' => (new Ride())->aggregate()
        };
    }
}
