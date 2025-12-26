<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        $user = $request->user();
        
        return [
            'comment_id' => $this->comment_id,
            'game_id' => $this->game_id,
            'user_id' => $this->user_id,
            'text' => $this->text,
            'short_text' => $this->short_text,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'game' => $this->whenLoaded('game', function () {
                return [
                    'id' => $this->game->id,
                    'title' => $this->game->title,
                    'city' => $this->game->city,
                    'year' => $this->game->year,
                ];
            }),
            'is_friend' => $this->when(isset($this->is_friend), function () {
                return $this->is_friend;
            }, $user ? $user->isFollowing($this->user_id) : false),
            'is_current_user' => $this->when(isset($this->is_current_user), function () {
                return $this->is_current_user;
            }, $user ? $user->id == $this->user_id : false),
            'can_edit' => $user ? $this->canEdit($user) : false,
            'can_delete' => $user ? $this->canDelete($user) : false,
        ];
    }
}