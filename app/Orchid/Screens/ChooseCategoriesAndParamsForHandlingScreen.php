<?php

namespace App\Orchid\Screens;

use App\Models\Category;
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
        return [
            'categories' => Category::all()
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
                    ->width('400')
                    ->render(function (Category $category) {
                        return Str::limit($category->title);
                    }),

                TD::make('', '')
                    ->render(function (Category $category) {
                        return Group::make([
                            Button::make('Выбрать параметры')
                                ->method('chooseCategory')
                                ->type(Color::PRIMARY())
                                ->parameters([
                                    'id' => $category->id
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
