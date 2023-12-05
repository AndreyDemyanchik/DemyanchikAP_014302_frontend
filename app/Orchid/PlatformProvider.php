<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Выбор категорий и параметров')
                ->icon('folder')
                ->route('platform.chooseCategoriesAndParamsForHandling')
                ->permission('platform.chooseCategoriesAndParamsForHandling'),

            /*Menu::make('Выбор стат. данных для визуализации')
                ->icon('folder-alt')
                ->route('platform.chooseStatVisualization')
                ->permission('platform.chooseStatVisualization'),*/

            Menu::make('Запуск визуализации')
                ->icon('control-forward')
                ->route('platform.startHandlingStatData')
                ->permission('platform.startHandlingStatData'),

            /*Menu::make('Скачать изображение визуализации')
                ->icon('cloud-download')
                ->route('platform.formImgVisualization')
                ->permission('platform.formImgVisualization'),*/

            Menu::make('Генерация отчёта')
                ->icon('browser')
                ->route('platform.generateReport')
                ->permission('platform.generateReport'),

            /*Menu::make('Chart tools')
                ->icon('bar-chart')
                ->route('platform.example.charts'),*/

            Menu::make(__('Пользователи'))
                ->icon('user')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Права доступа')),

            Menu::make(__('Роли'))
                ->icon('lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles'),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
            ItemPermission::group(__('Администратор'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
            ItemPermission::group(__('Менеджер'))
                ->addPermission('platform.chooseCategoriesAndParamsForHandling', __('Выбор категорий и параметров'))
                ->addPermission('platform.chooseStatVisualization', __('Выбор стат. данных для визуализации'))
                ->addPermission('platform.startHandlingStatData', __('Запуск визуализации'))
                ->addPermission('platform.formImgVisualization', __('Скачать изображение визуализации'))
                ->addPermission('platform.generateReport', __('Генерация отчёта'))
        ];
    }
}
