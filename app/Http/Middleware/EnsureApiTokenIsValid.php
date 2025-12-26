<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureApiTokenIsValid
{
    public function handle(Request $request, Closure $next)
    {
        // Проверяем заголовок Authorization
        if (!$request->bearerToken()) {
            return response()->json([
                'success' => false,
                'message' => 'Токен доступа отсутствует',
                'error' => 'Missing access token'
            ], 401);
        }

        // Проверяем валидность токена
        if (!Auth::guard('api')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Неверный или просроченный токен доступа',
                'error' => 'Invalid access token'
            ], 401);
        }

        return $next($request);
    }
}