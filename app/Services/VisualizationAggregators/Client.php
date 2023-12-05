<?php

declare(strict_types=1);

namespace App\Services\VisualizationAggregators;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Client extends AggregatorsFactory
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
        if (in_array('avgAge', array_keys($this->entity->fields))) {
            $this->result['avgAge'] = [
                'chartType' => 'text',
                'chartName' => 'Средний возраст клиентов',
                'data' => $this->countAvgAge()
            ];
        }

        if (in_array('changingClientsWithSubscription', array_keys($this->entity->fields))) {
            $this->result['changingClientsWithSubscription'] = [
                'chartType' => 'chartLineExample',
                'chartName' => 'Изменение количества клиентов с подпиской',
                'data' => $this->countChangingClientsWithSubscription()
            ];
        }

        if (in_array('absoluteChangingClientsQuantityByTime', array_keys($this->entity->fields))) {
            $this->result['absoluteChangingClientsQuantityByTime'] = [
                'chartType' => 'chartLineExample',
                'chartName' => 'Изменение количества клиентов во времени',
                'data' => $this->countAbsoluteChangingClientsQuantityByTime()
            ];
        }

        return $this->result;
    }

    /**
     * @return float
     */
    private function countAvgAge(): float
    {
        $clients = \App\Models\Client::select('date_of_birth')->get(); // получить всех клиентов из базы данных

        $totalYears = 0;

        foreach ($clients as $client) {
            $dateOfBirth = new Carbon($client->date_of_birth); // создание объекта Carbon из даты рождения клиента
            $totalYears += $dateOfBirth->age; // добавить возраст клиента к общему возрасту
        }

        return (double) $clients->count() > 0 ? $totalYears / $clients->count() : 0;
    }

    /**
     * @return array
     */
    private function countChangingClientsWithSubscription(): array
    {
        $activeSubscribers = \App\Models\Client::where('is_subscription_active', 1)
            ->pluck('created_at')->groupBy(fn ($date) => Carbon::parse($date)->format('Y-m'));

        return [
            'values' => range(1, count($activeSubscribers)),
            'labels' => TimePeriodsDivider::divideByMonths($activeSubscribers)
        ];
    }

    /**
     * @return array
     */
    private function countAbsoluteChangingClientsQuantityByTime(): array
    {
        $subscribers = \App\Models\Client::select('created_at')
            ->get()
            ->pluck('created_at')
            ->groupBy(fn ($date) => Carbon::parse($date)->format('Y-m'));

        return [
            'values' => range(1, count($subscribers)),
            'labels' => TimePeriodsDivider::divideByMonths($subscribers)
        ];
    }
}
