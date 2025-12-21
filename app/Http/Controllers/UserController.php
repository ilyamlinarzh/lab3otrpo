<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OlympicGame;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['show']);
    }

    public function show($id)
    {
        $user = User::withCount([
            'olympicGames' => function($query) {
                $query->whereNull('deleted_at');
            },
            'followers',
            'following'
        ])->findOrFail($id);

        $currentUser = Auth::user();
        
        $isCurrentUser = $currentUser->id == $user->id;
        $isFollowing = false;
        $isFollowedBy = false;
        $isMutualFriends = false;
        
        if (!$isCurrentUser) {
            $isFollowing = $currentUser->isFollowing($user->id);
            $isFollowedBy = $user->isFollowing($currentUser->id);
            $isMutualFriends = $isFollowing && $isFollowedBy;
        }
        
        $recentGames = $user->olympicGames()
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('users.show', compact(
            'user', 
            'currentUser',
            'isCurrentUser',
            'isFollowing',
            'isFollowedBy',
            'isMutualFriends',
            'recentGames'
        ));
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $sort = $request->get('sort', 'newest');
        
        $query = User::withCount([
            'olympicGames' => function($query) {
                $query->whereNull('deleted_at');
            },
            'followers',
            'following'
        ]);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        switch ($sort) {
            case 'games':
                $query->orderBy('olympic_games_count', 'desc');
                break;
            case 'followers':
                $query->orderBy('followers_count', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $users = $query->paginate(20);
        
        $currentUser = Auth::user();
        $followingIds = $currentUser ? $currentUser->following()->pluck('author_user_id')->toArray() : [];
        
        return view('users.index', compact(
            'users', 
            'search', 
            'sort',
            'currentUser',
            'followingIds'
        ));
    }

    public function games($id)
    {
        $user = User::findOrFail($id);
        
        $games = $user->olympicGames()
            ->whereNull('deleted_at')
            ->withCount('comments')
            ->orderBy('year', 'desc')
            ->paginate(12);
        
        $currentUser = Auth::user();
        $isCurrentUser = $currentUser && $currentUser->id == $user->id;
        
        return view('users.games', compact('user', 'games', 'isCurrentUser'));
    }

    public function followers($id)
    {
        $user = User::findOrFail($id);
        
        $followers = $user->followers()
            ->withCount('olympicGames')
            ->paginate(20);
        
        $currentUser = Auth::user();
        $isCurrentUser = $currentUser && $currentUser->id == $user->id;
        
        return view('users.followers', compact('user', 'followers', 'isCurrentUser'));
    }

    public function following($id)
    {
        $user = User::findOrFail($id);
        
        $following = $user->following()
            ->withCount('olympicGames')
            ->paginate(20);
        
        $currentUser = Auth::user();
        $isCurrentUser = $currentUser && $currentUser->id == $user->id;
        
        return view('users.following', compact('user', 'following', 'isCurrentUser'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Проверяем что пользователь редактирует свой профиль
        if (Auth::id() != $user->id) {
            abort(403, 'Вы можете редактировать только свой профиль');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $user->name = $validated['name'];
        
        if (isset($validated['bio'])) {
            $user->bio = $validated['bio'];
        }
        
        $user->save();
        
        return back()->with('success', 'Профиль успешно обновлен');
    }
}