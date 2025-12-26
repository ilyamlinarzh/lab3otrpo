<?php

namespace App\Policies;

use App\Models\User;
use App\Models\OlympicGame;
use Illuminate\Auth\Access\HandlesAuthorization;

class OlympicGamePolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, OlympicGame $game): bool
    {
        // Если игра удалена, только администратор может видеть
        if (!is_null($game->deleted_at)) {
            return $user->is_admin;
        }
        return true;
    }

    public function create(User $user): bool
    {
        return $user !== null;
    }

    public function update(User $user, OlympicGame $game): bool
    {
        return $game->user_id === $user->id || $user->is_admin;
    }

    public function delete(User $user, OlympicGame $game): bool
    {
        return $game->user_id === $user->id || $user->is_admin;
    }

    public function restore(User $user, OlympicGame $game): bool
    {
        return $user->is_admin;
    }

    public function forceDelete(User $user, OlympicGame $game): bool
    {
        return $user->is_admin;
    }

    public function isFriend(User $user, OlympicGame $game): bool
    {
        return $user->isFollowing($game->user_id);
    }
}