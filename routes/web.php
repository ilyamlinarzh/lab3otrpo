<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OlympicGameController;
use App\Http\Controllers\CommentController;


Route::resource('olympic-games', OlympicGameController::class);
Route::get('/', [OlympicGameController::class, 'index']);

Route::delete('/olympic-games/{id}/force-delete', [OlympicGameController::class, 'forceDelete'])
    ->name('olympic-games.force-delete');
    
Route::post('/olympic-games/{id}/restore', [OlympicGameController::class, 'restore'])
    ->name('olympic-games.restore');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::get('/olympic-games/{gameId}/comments', [CommentController::class, 'gameComments'])->name('games.comments');



require __DIR__.'/auth.php';
