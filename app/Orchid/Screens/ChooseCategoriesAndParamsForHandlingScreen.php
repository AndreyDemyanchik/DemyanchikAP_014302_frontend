<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Repository;

class ChooseCategoriesAndParamsForHandlingScreen extends Screen
{
    /**
     * @var array|string[]
     */
    private array $categoriesRoutesMatch = [
        1 => 'platform.categoryClients',
        2 => 'platform.categoryScooters',
        3 => 'platform.categoryMalfunctions',
        4 => 'platform.categoryRides'
    ];

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $categories = [];
        $client = new Client();

        $response = $client->get('http://kicksharing-management-system-backend/api/categories');
        $categoriesResponse = json_decode($response->getBody(), true);

        foreach ($categoriesResponse as $categoryResponse) {
            $categories[] = new Repository([
                'id' => $categoryResponse['id'],
                'title' => $categoryResponse['title']
            ]);
        }

        return [
            'categories' => $categories
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Выбор категорий и параметров';
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
            Layout::table('categories', [
                TD::make('title', 'Название')
                    ->width('400'),

                TD::make('', '')
                    ->render(function (Repository $item) {
                        return Group::make([
                            Button::make('Выбрать параметры')
                                ->method('chooseCategory')
                                ->type(Color::PRIMARY())
                                ->parameters([
                                    'id' => $item['id']
                                ]),
                        ])->autoWidth();
                    })
            ])
        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function chooseCategory(Request $request): RedirectResponse
    {
        return redirect()->route($this->categoriesRoutesMatch[$request->id]);
    }
}
