<?php

namespace App\Orchid\Screens;

use App\Http\Controllers\MalfunctionController;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class ChooseMalfunctionsParamsScreen extends Screen
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
        return 'Выбрать параметры визуализации неисправностей';
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
                    CheckBox::make('malfunctionsTypes')
                        ->title('Распределение по типам неисправностей')
                        ->sendTrueOrFalse(),

                    CheckBox::make('malfunctionsByMakers')
                        ->title('Распределение по неисправностям конкретных производителей')
                        ->sendTrueOrFalse(), //

                    CheckBox::make('rangeBySeverity')
                        ->title('Ранжирование по серьёзности поломок')
                        ->sendTrueOrFalse(),

                    CheckBox::make('avgRepairingTimeByMakers')
                        ->title('Среднее время починки по производителям')
                        ->sendTrueOrFalse(),

                    CheckBox::make('malfunctionsChanging')
                        ->title('Динамика неисправностей по времени')
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
        (new MalfunctionController())->create($request->except(['_token']));
    }
}
