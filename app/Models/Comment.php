<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $primaryKey = 'comment_id';

    protected $fillable = [
        'game_id',
        'user_id',
        'text'
    ];

    public static function rules()
    {
        return [
            'text' => 'required|string|max:500',
            'game_id' => 'required|exists:olympic_games,id',
            'user_id' => 'required|exists:users,id'
        ];
    }

    public function game()
    {
        return $this->belongsTo(OlympicGame::class, 'game_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function setTextAttribute($value)
    {
        $this->attributes['text'] = trim($value);
    }


    public function getShortTextAttribute()
    {
        return strlen($this->text) > 100 
            ? substr($this->text, 0, 100) . '...' 
            : $this->text;
    }

    public function isAuthor(User $user)
    {
        return $this->user_id === $user->id;
    }

    public function canEdit(User $user)
    {
        return $this->isAuthor($user) || $user->is_admin;
    }

    public function canDelete(User $user)
    {
        return $this->isAuthor($user) || $user->is_admin;
    }
}