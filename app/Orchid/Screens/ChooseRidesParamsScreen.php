<?php

namespace App\Orchid\Screens;

use App\Http\Controllers\RideController;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class ChooseRidesParamsScreen extends Screen
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
        return 'Выбрать параметры визуализации по поездкам';
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
                    CheckBox::make('rideSubscriptionPercentage')
                        ->title('Распределение поездок по подпискам и без')
                        ->sendTrueOrFalse(),

                    CheckBox::make('moneyAmountByMonths')
                        ->title('Сумма денег заработанных с поездок ежемесячно')
                        ->sendTrueOrFalse(), //

                    CheckBox::make('avgDistance')
                        ->title('Средняя дистанция поездок')
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
        (new RideController())->create($request->except(['_token']));
    }
}
