<?php

use App\Http\Controllers\VisualizationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/admin');
Route::get('statData/visualization/start', [
    'as' => 'platform.startHandlingStatData',
    'uses' => 'App\Orchid\Screens\StartHandlingStatDataScreen@__invoke',
    'controller' => 'App\Orchid\Screens\StartHandlingStatDataScreen',
    'prefix' => '/admin/'
])->name('platform.startHandlingStatData');
