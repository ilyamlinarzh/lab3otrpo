<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OlympicGameController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;


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

Route::get('/feed', [FollowController::class, 'feed'])->name('follow.feed');

Route::post('/follow', [FollowController::class, 'follow'])->name('follow.subscribe');
Route::post('/unfollow', [FollowController::class, 'unfollow'])->name('follow.unsubscribe');


// Маршруты пользователей
Route::controller(UserController::class)->group(function () {
    // Список пользователей (доступен всем)
    Route::get('/users', 'index')->name('users.index');
    
    // Игры пользователя (доступны всем)
    Route::get('/users/{id}/games', 'games')->name('users.games');
    
    // Подписчики пользователя (доступны всем)
    // Route::get('/users/{id}/followers', 'followers')->name('users.followers');
    
    // // Подписки пользователя (доступны всем)
    // Route::get('/users/{id}/following', 'following')->name('users.following');
});

// Защищенные маршруты (только для авторизованных)
Route::middleware('auth')->group(function () {
    // Профиль пользователя
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    
    // Обновление профиля
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
});

Route::middleware(['auth'])->group(function () {
    // Страница профиля с API токенами
    Route::get('/profile/api', [ProfileController::class, 'index'])
        ->name('profile.index');
    
    Route::post('/profile/create-token', [ProfileController::class, 'createToken'])
        ->name('profile.create-token');
    
    Route::delete('/profile/revoke-token/{tokenId}', [ProfileController::class, 'revokeToken'])
        ->name('profile.revoke-token');
    
    Route::delete('/profile/revoke-all-tokens', [ProfileController::class, 'revokeAllTokens'])
        ->name('profile.revoke-all-tokens');
});


require __DIR__.'/auth.php';
