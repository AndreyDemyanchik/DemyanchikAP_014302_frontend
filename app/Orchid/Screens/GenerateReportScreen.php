<?php

namespace App\Orchid\Screens;

use App\Orchid\Layouts\Examples\ChartLineExample;
use App\Services\EntityDetector;
use App\Services\VisualizationAggregators\AggregatorsFactory;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Spatie\Browsershot\Browsershot;

class GenerateReportScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        /*Browsershot::url('http://kicksharing-management-system/admin/statData/visualization/start')
            ->setNodeBinary('C:\Program Files\nodejs\node.exe')
            ->setNpmBinary('C:\Program Files\nodejs\npm')
            ->setChromePath('C:\Program Files\Google\Chrome\Application\chrome.exe')*/
            // ->setNodeModulePath('C:\Users\ilies\AppData\Roaming\npm\node_modules')
            //->newHeadless()
            //->format('A4')->save('example.pdf');

        return [];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Генерация отчёта';
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
        return [
            Layout::columns([
                Layout::rows([
                    Quill::make('comments')
                        ->title('Комментарии к отчёту'),

                    Button::make('Сохранить')
                        ->method('generate')
                        ->type(Color::DEFAULT())
                        ->rawClick(),
                ])
            ])
        ];
    }

    public function generate(Request $request)
    {
        $dataForReport = [];
        $aggregatedData = (new AggregatorsFactory)->process();

        $titleForReportPrefix = 'Отчёт по визуализации данных категории: ';
        $visualizatedEntityTitleForReport = match (EntityDetector::process()->entity_title)
        {
            'clients' => $titleForReportPrefix . 'Клиенты',
            'malfunctions' => $titleForReportPrefix . 'Неполадки',
            'rides' => $titleForReportPrefix . 'Поездки',
            'scooters' => $titleForReportPrefix . 'Транспорт',
        };

        foreach ($aggregatedData as $key => $value) {
            if (
                isset($value['chartName'], $value['data']['values'], $value['data']['labels']) &&
                is_iterable($value['data'])
            ) {
                $dataForReport[$key] = ['chartName' => $value['chartName']];
                $dataForReport[$key] += array_combine(
                    $value['data']['labels'],
                    $value['data']['values']
                );
            } else {
                $dataForReport[] = $value['chartName'] . ': ' . $value['data'];
            }
        }

        $pdf = new Dompdf([
            'defaultFont' => 'DejaVu Serif'
        ]);

        $html = view('pdf', [
            'title' => $visualizatedEntityTitleForReport,
            'data' => $dataForReport,
            'comments' => $request->get('comments')
        ])->render();
        $pdf->loadHtml($html);
        $pdf->render();

        return $pdf->stream("document.pdf");
    }
}
