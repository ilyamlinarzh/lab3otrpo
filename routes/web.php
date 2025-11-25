<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OlympicGameController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::resource('olympic-games', OlympicGameController::class);
Route::get('/', [OlympicGameController::class, 'index']);

