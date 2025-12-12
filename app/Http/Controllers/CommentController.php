<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\OlympicGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Конструктор - применяем middleware
     */
    public function __construct()
    {
        // Только авторизованные пользователи могут создавать и удалять комментарии
        $this->middleware('auth')->except(['gameComments']);
    }

    /**
     * Создание нового комментария (POST)
     */
    public function store(Request $request)
    {
        // Валидация
        $validated = $request->validate([
            'game_id' => 'required|exists:olympic_games,id',
            'text' => 'required|string|max:500',
        ]);

        // Проверяем что игра существует и не удалена
        $game = OlympicGame::find($validated['game_id']);
        if (!$game) {
            return back()->with('error', 'Игра не найдена');
        }

        // Проверяем что игра не удалена (для обычных пользователей)
        if ($game->trashed() && !Auth::user()->is_admin) {
            return back()->with('error', 'Нельзя комментировать удаленную игру');
        }

        // Создаем комментарий
        $comment = new Comment();
        $comment->game_id = $validated['game_id'];
        $comment->user_id = Auth::id(); // Автоматически привязываем текущего пользователя
        $comment->text = $validated['text'];
        
        $comment->save();

        return back()->with('success', 'Комментарий добавлен!');
    }

    /**
     * Полное удаление комментария (DELETE)
     * Только автор или администратор
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        // Проверка прав через Gate (или прямую проверку)
        if (Gate::denies('delete-comment', $comment)) {
            abort(403, 'У вас нет прав для удаления этого комментария');
        }

        // Полное удаление
        $comment->delete();

        return back()->with('success', 'Комментарий удален!');
    }


    public function gameComments($gameId)
    {   
        
        $game = OlympicGame::findOrFail($gameId);
        
        if ($game->trashed() && (!Auth::check() || !Auth::user()->is_admin)) {
            abort(404);
        }

        $comments = Comment::where('game_id', $gameId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('olympic-games.comments', compact('game', 'comments'));
    }

}