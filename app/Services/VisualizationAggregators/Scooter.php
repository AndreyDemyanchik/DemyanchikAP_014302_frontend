<?php

namespace App\Services\VisualizationAggregators;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * @property $entity
 */
class Scooter extends AggregatorsFactory
{
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

        if (in_array('speedIncreasing', array_keys($this->entity->fields))) {
            $this->result['speedIncreasing'] = [
                'chartType' => 'chartBarExample',
                'chartName' => 'Топ 5 моделей по скорости разгона в единицу времени (коэффициент)',
                'data' => $this->countSpeedIncreasing()
            ];
        }

        if (in_array('absoluteChangingScootersQuantityByTime', array_keys($this->entity->fields))) {
            $this->result['absoluteChangingScootersQuantityByTime'] = [
                'chartType' => 'chartLineExample',
                'chartName' => 'Динамика изменения единиц транспорта за всё время по месяцам',
                'data' => $this->countAbsoluteChangingScootersQuantityByTime()
            ];
        }

        if (in_array('detectReliability', array_keys($this->entity->fields))) {
            $this->result['detectReliability'] = [
                'chartType' => 'chartPercentageExample',
                'chartName' => 'Самокаты с наибольшим числом поломок за всё время',
                'data' => $this->detectReliability()
            ];
        }

        if (in_array('rangeByCost', array_keys($this->entity->fields))) {
            $this->result['rangeByCost'] = [
                'chartType' => 'chartBarExample',
                'chartName' => 'Самые дорогие самокаты (сумма цен разблокировки и тарифа)',
                'data' => $this->rangeByCost()
            ];
        }

        if (in_array('rangeByMoneyIncome', array_keys($this->entity->fields))) {
            $this->result['rangeByMoneyIncome'] = [
                'chartType' => 'chartPieExample',
                'chartName' => 'Самые прибыльные самокаты по заработанным суммам',
                'data' => $this->rangeByMoneyIncome()
            ];
        }

        return $this->result;
    }

    /**
     * @return array
     */
    private function countTopThreeMakers(): array
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

    /**
     * @return array
     */
    private function countTopThreeModels(): array
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

    /**
     * @return array
     */
    private function countSpeedIncreasing(): array
    {
        $topScooters = \App\Models\Scooter::select('model', DB::raw('max_speed / weight as ratio'))
            ->orderByRaw('max_speed / weight DESC')
            ->take(5)
            ->get();

        $ratio = $topScooters->pluck('ratio') ?? [];
        $values = [];

        foreach ($ratio as $item) {
            $values[] = number_format($item, 2);
        }

        return [
            'labels' => $topScooters->pluck('model'),
            'values' => $values
        ];
    }

    /**
     * @return array
     */
    private function countAbsoluteChangingScootersQuantityByTime(): array
    {
        $scooters = \App\Models\Scooter::select('*')
            ->pluck('created_at')->groupBy(fn ($date) => Carbon::parse($date)->format('Y-m'));

        return [
            'values' => range(1, count($scooters)),
            'labels' => TimePeriodsDivider::divideByMonths($scooters)
        ];
    }

    /**
     * @return array
     */
    private function detectReliability(): array
    {
        $mostMalfunctioningScooters = \App\Models\Scooter::leftJoin
        ('scooter_malfunction', 'scooters.id', '=', 'scooter_malfunction.scooter_id')
            ->select('scooters.model', DB::raw('COUNT(scooter_malfunction.id) as malfunction_count'))
            ->groupBy('scooters.model')
            ->orderBy('malfunction_count', 'DESC')
            ->take(7)
            ->get();

        //dd($leastMalfunctioningScooters);

        return [
            'labels' => $mostMalfunctioningScooters->pluck('model')->all(),
            'values' => $mostMalfunctioningScooters->pluck('malfunction_count')->all()
        ];
    }

    /**
     * @return void
     */
    private function rangeByCost(): array
    {
        $expensiveScooters = \App\Models\Scooter::selectRaw('*, (rate + unlock_price) as total_price')
            ->orderByDesc('total_price')
            ->take(5)
            ->get();

        return [
            'labels' => $expensiveScooters->pluck('model')->all(),
            'values' => $expensiveScooters->pluck('total_price')->all()
        ];
    }

    private function rangeByMoneyIncome(): array
    {
        $topProfitableScooters = \App\Models\Ride::select('scooters.model',
            DB::raw('SUM(rides.price_total) as total_profit'))
            ->join('scooters', 'rides.scooter_id', '=', 'scooters.id')
            ->groupBy('scooters.model')
            ->orderByDesc('total_profit')
            ->limit(5)
            ->get();

        return [
            'labels' => $topProfitableScooters->pluck('model'),
            'values' => $topProfitableScooters->pluck('total_profit')
        ];
    }
}
