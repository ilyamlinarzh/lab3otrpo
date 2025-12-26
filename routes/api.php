<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OlympicGameController;
use App\Http\Controllers\Api\CommentController;


Route::get('/test', function () {
    return response()->json([
        'message' => 'API работает успешно!',
        'version' => '1.0',
        'timestamp' => now()->format('Y-m-d H:i:s')
    ]);
});

Route::prefix('olympic-games')->group(function () {
    Route::get('/', [OlympicGameController::class, 'index']); // GET список
    Route::get('/{id}', [OlympicGameController::class, 'show']); // GET одна запись
    
    Route::middleware(['auth:api', 'api.auth'])->group(function () {
        Route::post('/', [OlympicGameController::class, 'store']); // POST создание
        Route::put('/{id}', [OlympicGameController::class, 'update']); // PUT обновление
        Route::delete('/{id}', [OlympicGameController::class, 'destroy']); // DELETE удаление
    });
});

Route::prefix('comments')->group(function () {
    Route::get('/game/{gameId}', [CommentController::class, 'index']); // GET комментарии игры
    Route::get('/{id}', [CommentController::class, 'show']); // GET один комментарий
    
    Route::middleware(['auth:api', 'api.auth'])->group(function () {
        Route::post('/', [CommentController::class, 'store']); // POST создание
        Route::put('/{id}', [CommentController::class, 'update']); // PUT обновление
        Route::delete('/{id}', [CommentController::class, 'destroy']); // DELETE удаление
    });
});

Route::middleware(['auth:api', 'api.auth'])->get('/user', function (Request $request) {
    return response()->json([
        'success' => true,
        'data' => [
            'id' => $request->user()->id,
            'name' => $request->user()->name,
            'email' => $request->user()->email,
            'is_admin' => $request->user()->is_admin,
            'created_at' => $request->user()->created_at->format('Y-m-d H:i:s'),
        ]
    ]);
});