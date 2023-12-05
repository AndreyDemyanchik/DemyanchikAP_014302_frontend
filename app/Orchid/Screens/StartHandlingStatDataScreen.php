<?php

namespace App\Orchid\Screens;

use App\Orchid\Layouts\Examples\ChartBarExample;
use App\Orchid\Layouts\Examples\ChartLineExample;
use App\Orchid\Layouts\Examples\ChartPercentageExample;
use App\Orchid\Layouts\Examples\ChartPieExample;
use App\Services\VisualizationAggregators\AggregatorsFactory;
use DateTime;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class StartHandlingStatDataScreen extends Screen
{
    /**
     * @var array
     */
    private array $chartsLayout;

    /**
     * @var array
     */
    private array $chartsQuery;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $aggregatedData = (new AggregatorsFactory)->process();
        //dd($aggregatedData);
        $this->makeChartsLayout($aggregatedData);
        $this->makeQueryForCharts($aggregatedData);

        //dd($aggregatedData);
        return $this->chartsQuery;
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Результаты визуализации выбранных данных';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return $this->chartsLayout;
    }

    /**
     * @param array $visualizationData
     * @return void
     */
    public function makeChartsLayout(array $visualizationData): void
    {
        $i = 0;
        foreach ($visualizationData as $paramName => $visualizationDatum) {
            if ($visualizationDatum['chartType'] !== 'text') {
                $className = ucfirst($visualizationDatum['chartType']);

                switch ($className)
                {
                    case 'ChartLineExample':
                        $this->chartsLayout[] = Layout::columns([
                            ChartLineExample::make(
                                'charts' . $i,
                                $visualizationDatum['chartName']
                            )
                        ]);
                    break;

                    case 'ChartBarExample':
                        $this->chartsLayout[] = Layout::columns([
                            ChartBarExample::make(
                                'charts' . $i,
                                $visualizationDatum['chartName']
                            )
                        ]);
                    break;

                    case 'ChartPercentageExample':
                        $this->chartsLayout[] = Layout::columns([
                            ChartPercentageExample::make(
                                'charts' . $i,
                                $visualizationDatum['chartName']
                            )
                        ]);
                    break;

                    case 'ChartPieExample':
                        $this->chartsLayout[] = Layout::columns([
                            ChartPieExample::make(
                                'charts' . $i,
                                $visualizationDatum['chartName']
                            )
                        ]);
                    break;
                }

                $i++;
            } else {
                $this->chartsLayout[] = Layout::view('textParam', [
                    'name' => $visualizationDatum['chartName'],
                    'value' => $visualizationDatum['data']
                ]);
            }
        }
    }

    /**
     * @param array $visualizationData
     * @return void
     */
    private function makeQueryForCharts(array $visualizationData): void
    {
        $i = 0;
        foreach ($visualizationData as $visualizationDatum) {
            if (is_iterable($visualizationDatum['data'])) {
                $this->chartsQuery['charts' . $i] = [$visualizationDatum['data']];
                $i++;
            }
        }
    }
}
