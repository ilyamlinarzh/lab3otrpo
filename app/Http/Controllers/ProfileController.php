<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Token;

class ProfileController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $tokens = $user->tokens()->where('revoked', false)->get();
        
        return view('profile.index', compact('user', 'tokens'));
    }

    public function createToken(Request $request)
    {
        $request->validate([
            'token_name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        
        try {
            $tokenResult = $user->createToken($request->token_name);
            
            $plainTextToken = $tokenResult->accessToken;
            
            return back()
                ->with('success', 'Токен создан успешно!')
                ->with('plainTextToken', $plainTextToken);
                
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при создании токена: ' . $e->getMessage());
        }
    }

    public function revokeToken($tokenId)
    {
        try {
            $token = Token::find($tokenId);
            
            if (!$token) {
                return back()->with('error', 'Токен не найден.');
            }
            
            if ($token->user_id != Auth::id()) {
                return back()->with('error', 'У вас нет прав для отзыва этого токена.');
            }
            
            $token->revoke();
            
            return back()->with('success', 'Токен отозван успешно!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при отзыве токена: ' . $e->getMessage());
        }
    }

    public function revokeAllTokens()
    {
        try {
            $user = Auth::user();
            
            // Отзываем все токены пользователя
            $user->tokens()->update(['revoked' => true]);
            
            return back()->with('success', 'Все токены отозваны успешно!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при отзыве всех токенов: ' . $e->getMessage());
        }
    }
}