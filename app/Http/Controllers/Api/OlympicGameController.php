<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OlympicGameResource;
use App\Models\OlympicGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class OlympicGameController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        
        $sort = $request->get('sort', 'desc');
        $perPage = $request->get('per_page', 15);
        $year = $request->get('year');
        $city = $request->get('city');
        
        $query = OlympicGame::query();
        
        if (!$user || !$user->is_admin) {
            $query->whereNull('deleted_at');
        }
        
        if ($year) {
            $query->where('year', $year);
        }
        
        if ($city) {
            $query->where('city', 'like', "%{$city}%");
        }
        
        $sort = in_array(strtolower($sort), ['asc', 'desc']) ? $sort : 'desc';
        $query->orderBy('year', $sort);
        
        $games = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $games->items(),
            'meta' => [
                'current_page' => $games->currentPage(),
                'per_page' => $games->perPage(),
                'total' => $games->total(),
                'last_page' => $games->lastPage(),
            ],
            'links' => [
                'first' => $games->url(1),
                'last' => $games->url($games->lastPage()),
                'prev' => $games->previousPageUrl(),
                'next' => $games->nextPageUrl(),
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
            'title' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:2030',
            'short_description' => 'required|string|max:500',
            'detailed_description' => 'required|string',
            'fun_fact' => 'required|string|max:300',
            'image_filename' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = $user->id;
        
        $game = OlympicGame::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Олимпийские игры успешно созданы',
            'data' => new OlympicGameResource($game)
        ], 201);
    }

    public function show($id)
    {
        $user = Auth::guard('api')->user();
        
        if ($user && $user->is_admin) {
            $game = OlympicGame::withTrashed()->find($id);
        } else {
            $game = OlympicGame::find($id);
        }
        
        if (!$game) {
            return response()->json([
                'success' => false,
                'message' => 'Олимпийские игры не найдены'
            ], 404);
        }
        
        if (!$user && $game->trashed()) {
            return response()->json([
                'success' => false,
                'message' => 'Олимпийские игры не найдены'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => new OlympicGameResource($game)
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
        
        $game = OlympicGame::find($id);
        
        if (!$game) {
            return response()->json([
                'success' => false,
                'message' => 'Олимпийские игры не найдены'
            ], 404);
        }
        
        if (Gate::forUser($user)->denies('edit-game', $game)) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет прав для редактирования этой записи'
            ], 403);
        }
        
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:100',
            'year' => 'sometimes|required|integer|min:1900|max:2060',
            'short_description' => 'sometimes|required|string|max:500',
            'detailed_description' => 'sometimes|required|string',
            'fun_fact' => 'sometimes|required|string|max:300',
            'image_filename' => 'sometimes|nullable|string|max:255',
        ]);
        
        $game->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Олимпийские игры успешно обновлены',
            'data' => new OlympicGameResource($game)
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
        
        $game = OlympicGame::find($id);
        
        if (!$game) {
            return response()->json([
                'success' => false,
                'message' => 'Олимпийские игры не найдены'
            ], 404);
        }
        
        if (Gate::forUser($user)->denies('delete-game', $game)) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет прав для удаления этой записи'
            ], 403);
        }
        
        $game->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Олимпийские игры успешно удалены'
        ]);
    }
}