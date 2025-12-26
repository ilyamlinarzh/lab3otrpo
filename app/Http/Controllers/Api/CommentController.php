<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\OlympicGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function index(Request $request, $gameId)
    {
        $user = Auth::guard('api')->user();
        
        $game = OlympicGame::find($gameId);
        if (!$game) {
            return response()->json([
                'success' => false,
                'message' => 'Игра не найдена'
            ], 404);
        }
        
        if ($game->trashed() && (!$user || !$user->is_admin)) {
            return response()->json([
                'success' => false,
                'message' => 'Игра не найдена'
            ], 404);
        }
        
        $perPage = $request->get('per_page', 15);
        $sort = $request->get('sort', 'desc');
        
        $query = Comment::where('game_id', $gameId)
            ->with('user')
            ->orderBy('created_at', $sort);
        
        $comments = $query->paginate($perPage);
        
        if ($user) {
            $comments->getCollection()->transform(function ($comment) use ($user) {
                $comment->is_friend = $user->isFollowing($comment->user_id);
                $comment->is_current_user = $comment->user_id == $user->id;
                return $comment;
            });
        }
        
        return response()->json([
            'success' => true,
            'data' => $comments->items(),
            'meta' => [
                'current_page' => $comments->currentPage(),
                'per_page' => $comments->perPage(),
                'total' => $comments->total(),
                'last_page' => $comments->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Требуется аутентификация'
            ], 401);
        }

        $validated = $request->validate([
            'game_id' => 'required|exists:olympic_games,id',
            'text' => 'required|string|max:500',
        ]);

        $game = OlympicGame::find($validated['game_id']);
        if (!$game) {
            return response()->json([
                'success' => false,
                'message' => 'Игра не найдена'
            ], 404);
        }

        if ($game->trashed() && !$user->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Нельзя комментировать удаленную игру'
            ], 403);
        }

        $comment = new Comment();
        $comment->game_id = $validated['game_id'];
        $comment->user_id = $user->id;
        $comment->text = $validated['text'];
        $comment->save();

        $comment->load('user');

        $comment->is_friend = false;
        $comment->is_current_user = true;

        return response()->json([
            'success' => true,
            'message' => 'Комментарий успешно создан',
            'data' => new CommentResource($comment)
        ], 201);
    }

    public function show($id)
    {
        $user = Auth::guard('api')->user();
        
        $comment = Comment::with(['user', 'game'])->find($id);
        
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Комментарий не найден'
            ], 404);
        }
        if ($comment->game->trashed() && (!$user || !$user->is_admin)) {
            return response()->json([
                'success' => false,
                'message' => 'Комментарий недоступен'
            ], 403);
        }
        
        if ($user) {
            $comment->is_friend = $user->isFollowing($comment->user_id);
            $comment->is_current_user = $comment->user_id == $user->id;
        }
        
        return response()->json([
            'success' => true,
            'data' => new CommentResource($comment)
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Требуется аутентификация'
            ], 401);
        }
        
        $comment = Comment::find($id);
        
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Комментарий не найден'
            ], 404);
        }
        
        if (Gate::forUser($user)->denies('edit-comment', $comment)) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет прав для редактирования этого комментария'
            ], 403);
        }
        
        $validated = $request->validate([
            'text' => 'required|string|max:500',
        ]);
        
        $comment->text = $validated['text'];
        $comment->save();
        
        $comment->load('user');
        
        $comment->is_friend = $user->isFollowing($comment->user_id);
        $comment->is_current_user = $comment->user_id == $user->id;
        
        return response()->json([
            'success' => true,
            'message' => 'Комментарий успешно обновлен',
            'data' => new CommentResource($comment)
        ]);
    }

    public function destroy($id)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Требуется аутентификация'
            ], 401);
        }
        
        $comment = Comment::find($id);
        
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Комментарий не найден'
            ], 404);
        }
        
        if (Gate::forUser($user)->denies('delete-comment', $comment)) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет прав для удаления этого комментария'
            ], 403);
        }
        
        $comment->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Комментарий успешно удален'
        ]);
    }
}