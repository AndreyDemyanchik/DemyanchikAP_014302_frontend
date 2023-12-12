<?php

namespace App\Orchid\Screens;

ini_set('max_execution_time', 300);

use App\Orchid\Layouts\Examples\ChartLineExample;
use App\Services\EntityDetector;
use App\Services\VisualizationAggregators\AggregatorsFactory;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Spatie\Browsershot\Exceptions\CouldNotTakeBrowsershot
     */
    public function generate(Request $request)
    {
        Browsershot::url('http://kicksharing-management-system/admin/statData/visualization/start')
            ->setNodeBinary('C:\Program Files\nodejs\node.exe')
            ->setNpmBinary('C:\\Users\\ADMIN\\AppData\\Roaming\\npm')
            ->setDelay(3000)
            ->setOption('newHeadless', true)
            ->save('report.pdf');

        return response()->download('report.pdf');
    }
}
