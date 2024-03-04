<?php

namespace App\Orchid\Screens;

use App\Http\Controllers\ClientController;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class ChooseClientsParamsScreen extends Screen
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
        return 'Выбрать параметры визуализации клиентов';
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
                    CheckBox::make('avgAge')
                        ->title('Средний возраст клиентов')
                        ->sendTrueOrFalse(),

                    CheckBox::make('absoluteChangingClientsQuantityByTime')
                        ->title('Абсолютный прирост/убыток общего количества клиентов во времени')
                        ->sendTrueOrFalse(), //

                    CheckBox::make('changingClientsWithSubscription')
                        ->title('Прирост/убыток количества пользователей с подпиской')
                        ->sendTrueOrFalse(),

                    CheckBox::make('clientsDynamicsByMonth')
                        ->title('Динамика клиентов по месяцам')
                        ->sendTrueOrFalse(),

                    CheckBox::make('strongClientsDynamicsByMonth')
                        ->title('Динамика поездок постоянных клиентов по месяцам')
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
        $client = new Client();

        $client->post('http://kicksharing-management-system-backend/api/visualization/clients', [
            'json' => $request->except(['_token'])
        ]);
    }
}
