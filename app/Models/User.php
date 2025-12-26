<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    public function olympicGames()
    {
        return $this->hasMany(OlympicGame::class, 'user_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows',
            'subscriber_user_id',
            'author_user_id'
        )->withTimestamps('created_at');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 
            'author_user_id', // Этот пользователь - автор (на кого подписываются)
            'subscriber_user_id' // Подписчики
        )->withTimestamps('created_at');
    }

    public function follow($authorId)
    {
        if ($this->id == $authorId) {
            return false; // Нельзя подписаться на самого себя
        }

        return Follow::subscribe($this->id, $authorId);
    }

    public function unfollow($authorId)
    {
        return Follow::unsubscribe($this->id, $authorId);
    }

    public function followersCount()
    {
        return $this->followers()->count();
    }

    public function getAllGames($includeTrashed = false)
    {
        $query = $this->olympicGames();
        
        if ($includeTrashed) {
            $query->withTrashed();
        }
        
        return $query->orderBy('year', 'desc')->get();
    }

    public function isFollowing($authorId)
    {
        return $this->following()->where('author_user_id', $authorId)->exists();
    }

}
