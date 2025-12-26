<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OlympicGameResource extends JsonResource
{
    public function toArray($request)
    {
        $user = $request->user();
        
        return [
            'id' => $this->id,
            'title' => $this->title,
            'city' => $this->city,
            'year' => $this->year,
            'short_description' => $this->short_description,
            'detailed_description' => $this->detailed_description,
            'fun_fact' => $this->fun_fact,
            'image_filename' => $this->image_filename,
            'image_url' => $this->image_filename ? asset('img/' . $this->image_filename) : null,
            'user_id' => $this->user_id,
            'author' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'comments_count' => $this->whenLoaded('comments', function () {
                return $this->comments->count();
            }, 0),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'is_deleted' => !is_null($this->deleted_at),
            'deleted_at' => $this->when(!is_null($this->deleted_at), function () {
                return $this->deleted_at->format('Y-m-d H:i:s');
            }),
            'is_friend' => $user ? $user->isFollowing($this->user_id) : false,
            'is_owner' => $user ? $user->id == $this->user_id : false,
        ];
    }
}