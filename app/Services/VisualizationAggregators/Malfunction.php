<?php

namespace App\Services\VisualizationAggregators;

use Carbon\Carbon;
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

        if (in_array('avgRepairingTimeByMakers', array_keys($this->entity->fields))) {
            $this->result['avgRepairingTimeByMakers'] = [
                'chartType' => 'chartBarExample',
                'chartName' => 'Среднее время починки по производителям (в часах)',
                'data' => $this->countAvgRepairingTimeByMakers()
            ];
        }

        if (in_array('malfunctionsChanging', array_keys($this->entity->fields))) {
            $this->result['malfunctionsChanging'] = [
                'chartType' => 'chartLineExample',
                'chartName' => 'Динамика неисправностей по времени',
                'data' => $this->countMalfunctionsChanging()
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

    /**
     * @return array
     */
    private function countAvgRepairingTimeByMakers(): array
    {
        $averageRepairTime = \App\Models\Scooter::select(
            'scooters.maker as maker',
            DB::raw('AVG(TIMESTAMPDIFF(HOUR, scooter_malfunction.reported_at, scooter_malfunction.repaired_at))
            as average_repair_time_in_hours')
        )
            ->join('scooter_malfunction', 'scooters.id', '=', 'scooter_malfunction.scooter_id')
            ->groupBy('scooters.maker')
            ->limit(15)
            ->get();

        return [
            'labels' => $averageRepairTime->pluck('maker'),
            'values' => $averageRepairTime->pluck('average_repair_time_in_hours')
        ];
    }

    /**
     * @return array
     */
    private function countMalfunctionsChanging(): array
    {
        $malfunctions = \App\Models\ScooterMalfunction::select('*')
            ->pluck('reported_at')->groupBy(fn ($date) => Carbon::parse($date)->format('Y-m'));

        return [
            'values' => range(1, count($malfunctions)),
            'labels' => TimePeriodsDivider::divideByMonths($malfunctions)
        ];
    }
}
