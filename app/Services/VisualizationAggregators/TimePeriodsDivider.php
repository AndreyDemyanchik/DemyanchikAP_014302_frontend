<?php

declare(strict_types=1);

namespace App\Services\VisualizationAggregators;

use Carbon\CarbonPeriod;

class TimePeriodsDivider
{
    /**
     * @param $subscribers
     * @return array
     */
    public static function divideByMonths($subscribers): array
    {
        $startDate = $subscribers->keys()->sort()->first();
        $endDate = $subscribers->keys()->sort()->last();

        $period = new CarbonPeriod($startDate, '1 month', $endDate);

        foreach ($period as $date) {
            $months[] = $date->format('Y-m');
        }

        return $months;
    }
}
