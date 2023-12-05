<?php

declare(strict_types=1);

namespace App\Services\VisualizationAggregators;

use Carbon\Carbon;

class Ride extends AggregatorsFactory
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
        if (in_array('rideSubscriptionPercentage', array_keys($this->entity->fields))) {
            $this->result['rideSubscriptionPercentage'] = [
                'chartType' => 'chartBarExample',
                'chartName' => 'Распределение поездок по подпискам и без',
                'data' => $this->countRideSubscriptionPercentage()
            ];
        }

        if (in_array('moneyAmountByMonths', array_keys($this->entity->fields))) {
            $this->result['moneyAmountByMonths'] = [
                'chartType' => 'chartLineExample',
                'chartName' => 'Сумма денег заработанных с поездок ежемесячно',
                'data' => $this->countMoneyAmountByMonths()
            ];
        }

        if (in_array('avgDistance', array_keys($this->entity->fields))) {
            $this->result['avgDistance'] = [
                'chartType' => 'text',
                'chartName' => 'Средняя дистанция поездок',
                'data' => $this->countAvgDistance()
            ];
        }

        return $this->result;
    }

    /**
     * @return array
     */
    private function countRideSubscriptionPercentage(): array
    {
        return [
            'values' => [
                \App\Models\Ride::where('is_subscription_ride', 1)->count(),
                \App\Models\Ride::where('is_subscription_ride', 0)->count()
            ],
            'labels' => [
                'С подпиской',
                'Без подписки'
            ]
        ];
    }

    private function countMoneyAmountByMonths()
    {
        $ridesDates = \App\Models\Ride::where('price_total', '>', '1')
            ->pluck('created_at')->groupBy(fn ($date) => Carbon::parse($date)->format('Y-m'));

        $sums = \App\Models\Ride::select('price_total', 'created_at')->get()->toArray();
        $months = TimePeriodsDivider::divideByMonths($ridesDates);

        $sumsResult = [];

        foreach ($months as $date) {
            $totalPrice = 0;

            foreach ($sums as $price) {
                if (date("Y-m", strtotime($price['created_at'])) === $date) {
                    $totalPrice = $price['price_total'];
                    break;
                }
            }

            $sumsResult[] = $totalPrice;
        }


        return [
            'values' => $sumsResult,
            'labels' => TimePeriodsDivider::divideByMonths($ridesDates)
        ];
    }

    private function countAvgDistance()
    {
        return \App\Models\Ride::average('distance') . ' км.';
    }
}
