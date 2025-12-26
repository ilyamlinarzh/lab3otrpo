<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\OlympicGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['gameComments']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:olympic_games,id',
            'text' => 'required|string|max:500',
        ]);

        $game = OlympicGame::find($validated['game_id']);
        if (!$game) {
            return back()->with('error', 'Игра не найдена');
        }

        if ($game->trashed() && !Auth::user()->is_admin) {
            return back()->with('error', 'Нельзя комментировать удаленную игру');
        }

        $comment = new Comment();
        $comment->game_id = $validated['game_id'];
        $comment->user_id = Auth::id(); // Автоматически привязываем текущего пользователя
        $comment->text = $validated['text'];
        
        $comment->save();

        return back()->with('success', 'Комментарий добавлен!');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if (Gate::denies('delete-comment', $comment)) {
            abort(403, 'У вас нет прав для удаления этого комментария');
        }

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

        // Определяем для каждого комментария, является ли автор другом
        if (Auth::check()) {
            $currentUser = Auth::user();
            
            $comments = $comments->map(function($comment) use ($currentUser) {
                $comment->isFriend = $currentUser->isFollowing($comment->user_id);
                $comment->isCurrentUser = $comment->user_id == $currentUser->id;
                return $comment;
            });
        }

        return view('olympic-games.comments', compact('game', 'comments'));
    }
}