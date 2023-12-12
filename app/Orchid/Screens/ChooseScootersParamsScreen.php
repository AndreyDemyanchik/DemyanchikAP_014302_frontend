<?php

namespace App\Orchid\Screens;

use App\Http\Controllers\ScooterController;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class ChooseScootersParamsScreen extends Screen
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
        return 'Выбрать параметры визуализации транспорта';
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
                    CheckBox::make('topThreeMakers')
                        ->title('Топ-3 производителей')
                        ->sendTrueOrFalse(),

                    CheckBox::make('topThreeModels')
                        ->title('Топ-3 моделей')
                        ->sendTrueOrFalse(),

                    CheckBox::make('avgSpeed')
                        ->title('Ранжирование по скорости')
                        ->sendTrueOrFalse(),

                    CheckBox::make('speedIncreasing')
                        ->title('Данные по скорости разгона в единицу времени')
                        ->sendTrueOrFalse(),

                    CheckBox::make('absoluteChangingScootersQuantityByTime')
                        ->title('Динамика изменения единиц транспорта за всё время по месяцам')
                        ->sendTrueOrFalse(),

                    CheckBox::make('detectReliability')
                        ->title('Самокаты с наибольшим числом поломок за всё время')
                        ->sendTrueOrFalse(),

                    CheckBox::make('rangeByCost')
                        ->title('Самые дорогие самокаты (сумма цен разблокировки и тарифа)')
                        ->sendTrueOrFalse(),

                    CheckBox::make('rangeByMoneyIncome')
                        ->title('Самые прибыльные самокаты по заработанным суммам')
                        ->sendTrueOrFalse(),

                    Button::make('Сохранить')
                        ->method('submit')
                        ->type(Color::DEFAULT()),
                ])
            ])
        ];
    }

    public function submit(Request $request)
    {
        (new ScooterController())->create($request->except(['_token']));
    }
}
