<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use App\Models\OlympicGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class FollowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function feed()
    {
        $user = Auth::user();
        
        $followingIds = $user->following()->pluck('author_user_id');
        
        if ($followingIds->isEmpty()) {
            
            return view('follow.feed', [
                'games' => collect(), // пустая коллекция
                'followingIds' => $followingIds,
                'emptyFeed' => true
            ]);
        }
        
        $games = OlympicGame::whereIn('user_id', $followingIds)
            ->whereNull('deleted_at')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('follow.feed', [
            'games' => $games,
            'followingIds' => $followingIds,
            'emptyFeed' => false
        ]);
    }

    public function follow(Request $request)
    {
        $request->validate([
            'author_id' => 'required|exists:users,id'
        ]);
        
        $user = Auth::user();
        $authorId = $request->input('author_id');
        
        if ($user->id == $authorId) {
            return back()->with('error', 'Нельзя подписаться на самого себя');
        }
        
        if ($user->isFollowing($authorId)) {
            return back()->with('info', 'Вы уже подписаны на этого пользователя');
        }
        
        try {
            $follow = new Follow();
            $follow->subscriber_user_id = $user->id;
            $follow->author_user_id = $authorId;
            $follow->save();
            
            $user->load('following');
            
            return back()->with('success', 'Вы успешно подписались на пользователя');
            
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'unique')) {
                return back()->with('info', 'Вы уже подписаны на этого пользователя');
            }
            
            return back()->with('error', 'Ошибка при подписке: ' . $e->getMessage());
        }
    }

    public function unfollow(Request $request)
    {
        $request->validate([
            'author_id' => 'required|exists:users,id'
        ]);
        
        $user = Auth::user();
        $authorId = $request->input('author_id');
        
        if (!$user->isFollowing($authorId)) {
            return back()->with('info', 'Вы не подписаны на этого пользователя');
        }
        
        try {
            Follow::withoutEvents(function () use ($user, $authorId) {
                Follow::where('subscriber_user_id', $user->id)
                    ->where('author_user_id', $authorId)
                    ->delete();
                
                Follow::where('subscriber_user_id', $authorId)
                    ->where('author_user_id', $user->id)
                    ->delete();
            });
            
            $user->load('following');
            
            return back()->with('success', 'Вы отписались от пользователя. Взаимная подписка удалена.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при отписке: ' . $e->getMessage());
        }
    }

    public function following()
    {
        $user = Auth::user();
        $following = $user->following()
            ->withCount('olympicGames as games_count')
            ->paginate(20);
        
        return view('follow.following', compact('following'));
    }

    public function followers()
    {
        $user = Auth::user();
        $followers = $user->followers()
            ->withCount('olympicGames as games_count')
            ->paginate(20);
        
        return view('follow.followers', compact('followers'));
    }
}