<?php

namespace App\Services\VisualizationAggregators;

use Illuminate\Support\Facades\DB;

/**
 * @property $entity
 */
class Scooter extends AggregatorsFactory
{
    /**
     * @var array|string[]
     */
    private array $fieldsWithCharts = [
        'topThreeMakers' => 'chartPieExample',
        'topThreeModels' => 'chartPieExample',
        'avgSpeed' => 'text'
    ];

    private array $result = [];

    public function aggregate()
    {
        if (in_array('topThreeMakers', array_keys($this->entity->fields))) {
            $this->result['topThreeMakers'] = [
                'chartType' => 'chartPieExample',
                'chartName' => 'Топ-3 производителя',
                'data' => $this->countTopThreeMakers()
            ];
        }

        if (in_array('topThreeModels', array_keys($this->entity->fields))) {
            $this->result['topThreeModels'] = [
                'chartType' => 'chartPieExample',
                'chartName' => 'Топ-3 модели',
                'data' => $this->countTopThreeModels()
            ];
        }

        if (in_array('avgSpeed', array_keys($this->entity->fields))) {
            $this->result['avgSpeed'] = [
                'chartType' => 'text',
                'chartName' => 'Средняя скорость самоката',
                'data' => $this->countAvgSpeed() . ' км/ч'
            ];
        }

        return $this->result;
    }

    private function countTopThreeMakers()
    {
        $topManufacturers = \App\Models\Scooter::select('maker', DB::raw('COUNT(*) as count'))
            ->groupBy('maker')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(3)
            ->get();

        foreach ($topManufacturers as $manufacturer) {
            $makersData['values'][] = $manufacturer->count;
            $makersData['labels'][] = $manufacturer->maker;
        }

        return $makersData;
    }

    private function countTopThreeModels()
    {
        $topModels = \App\Models\Scooter::select('model', DB::raw('COUNT(*) as count'))
            ->groupBy('model')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(3)
            ->get();

        foreach ($topModels as $model) {
            $modelsData['values'][] = $model->count;
            $modelsData['labels'][] = $model->model;
        }

        return $modelsData;
    }

    /**
     * @return float
     */
    private function countAvgSpeed(): float
    {
        return \App\Models\Scooter::average('max_speed');
    }
}
