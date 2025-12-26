<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can edit the comment.
     */
    public function edit(User $user, Comment $comment): bool
    {
        return $comment->isAuthor($user) || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the comment.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $comment->isAuthor($user) || $user->is_admin;
    }
}