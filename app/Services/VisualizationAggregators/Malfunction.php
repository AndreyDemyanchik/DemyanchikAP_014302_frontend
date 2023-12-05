<?php

namespace App\Services\VisualizationAggregators;

use Illuminate\Support\Facades\DB;

class Malfunction extends AggregatorsFactory
{
    /**
     * @var array
     */
    private array $result = [];

    /**
     * @return array
     */
    public function aggregate(): array
    {
        if (in_array('malfunctionsTypes', array_keys($this->entity->fields))) {
            $this->result['malfunctionsTypes'] = [
                'chartType' => 'chartBarExample',
                'chartName' => 'Распределение по типам неисправностей',
                'data' => $this->retrieveMalfunctionsTypes()
            ];
        }

        if (in_array('malfunctionsByMakers', array_keys($this->entity->fields))) {
            $this->result['malfunctionsByMakers'] = [
                'chartType' => 'chartPercentageExample',
                'chartName' => 'Распределение по неисправностям конкретных производителей',
                'data' => $this->retrieveMalfunctionsByMakers()
            ];
        }

        if (in_array('rangeBySeverity', array_keys($this->entity->fields))) {
            $this->result['rangeBySeverity'] = [
                'chartType' => 'chartPieExample',
                'chartName' => 'Ранжирование по серьёзностям поломки',
                'data' => $this->rangeBySeverity()
            ];
        }

        return $this->result;
    }

    /**
     * @return array
     */
    private function retrieveMalfunctionsTypes(): array
    {
        $malfunctionsWithTypes = \App\Models\Malfunction::select('name', DB::raw('count(*) as count'))
            ->groupBy('name')
            ->get();

        return [
            'values' => $malfunctionsWithTypes->pluck('count')->toArray(),
            'labels' => $malfunctionsWithTypes->pluck('name')->toArray()
        ];
    }

    /**
     * @return array
     */
    private function retrieveMalfunctionsByMakers(): array
    {
        $scooterMalfunctionCount = \App\Models\ScooterMalfunction::select('scooters.maker',
            DB::raw('count(*) as count'))
            ->join('scooters', 'scooter_malfunction.scooter_id', '=', 'scooters.id')
            ->groupBy('scooters.maker')
            ->get();

        return [
            'values' => $scooterMalfunctionCount->pluck('count')->toArray(),
            'labels' => $scooterMalfunctionCount->pluck('maker')->toArray()
        ];
    }

    /**
     * @return array
     */
    private function rangeBySeverity(): array
    {
        $malfunctionCounts = \App\Models\Malfunction::select('severity', DB::raw('count(*) as count'))
            ->groupBy('severity')
            ->get();

        return [
            'values' => $malfunctionCounts->pluck('count')->toArray(),
            'labels' => array_map(function ($severity) {
                return match ($severity) {
                    'LOW' => 'Низкая',
                    'MODERATE' => 'Умеренная',
                    'HIGH' => 'Критическая'
                };
            }, $malfunctionCounts->pluck('severity')->toArray())
        ];
    }
}
