<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OlympicGameController;


Route::resource('olympic-games', OlympicGameController::class);
Route::get('/', [OlympicGameController::class, 'index']);

Route::delete('/olympic-games/{id}/force-delete', [OlympicGameController::class, 'forceDelete'])
    ->name('olympic-games.force-delete');
    
Route::post('/olympic-games/{id}/restore', [OlympicGameController::class, 'restore'])
    ->name('olympic-games.restore');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::post('/olympic-games/{id}/restore', [OlympicGameController::class, 'restore'])
//         ->name('olympic-games.restore');
// });



require __DIR__.'/auth.php';
