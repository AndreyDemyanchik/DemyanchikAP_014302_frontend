<?php

declare(strict_types=1);

namespace App\Services\VisualizationAggregators;

class MonthsDetector
{
    /**
     * @param int $num
     * @return string
     */
    public static function getMonthNameByNumber(int $num): string
    {
        return match($num)
        {
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь'
        };
    }
}
