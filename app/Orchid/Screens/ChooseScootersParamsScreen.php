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
