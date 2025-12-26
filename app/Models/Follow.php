<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $primaryKey = 'follow_id';

    protected $fillable = [
        'subscriber_user_id',
        'author_user_id'
    ];

    public $timestamps = false;
    protected $dates = ['created_at'];

    public static function rules()
    {
        return [
            'author_user_id' => 'required|exists:users,id|different:subscriber_user_id'
        ];
    }

    public function subscriber()
    {
        return $this->belongsTo(User::class, 'subscriber_user_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_user_id');
    }

    public static function existsSubscription($subscriberId, $authorId)
    {
        return self::where('subscriber_user_id', $subscriberId)
                  ->where('author_user_id', $authorId)
                  ->exists();
    }

    public static function subscribe($subscriberId, $authorId)
    {
        if ($subscriberId == $authorId) {
            throw new \Exception('Нельзя подписаться на самого себя');
        }

        return self::firstOrCreate([
            'subscriber_user_id' => $subscriberId,
            'author_user_id' => $authorId
        ]);
    }

    public static function unsubscribe($subscriberId, $authorId)
    {
        return self::where('subscriber_user_id', $subscriberId)
                  ->where('author_user_id', $authorId)
                  ->delete();
    }

    public static function getFollowers($userId)
    {
        return self::where('author_user_id', $userId)
                  ->with('subscriber')
                  ->get()
                  ->pluck('subscriber');
    }

    public static function getSubscriptions($userId)
    {
        return self::where('subscriber_user_id', $userId)
                  ->with('author')
                  ->get()
                  ->pluck('author');
    }

    public static function isFollowing($subscriberId, $authorId)
    {
        return self::where('subscriber_user_id', $subscriberId)
                  ->where('author_user_id', $authorId)
                  ->exists();
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($follow) {
            $reverseExists = self::where('subscriber_user_id', $follow->author_user_id)
                ->where('author_user_id', $follow->subscriber_user_id)
                ->exists();
            
            if (!$reverseExists) {
                self::create([
                    'subscriber_user_id' => $follow->author_user_id,
                    'author_user_id' => $follow->subscriber_user_id,
                    'created_at' => now()
                ]);
            }
        });

        static::deleting(function ($follow) {
            $authorId = $follow->author_user_id;
            $subscriberId = $follow->subscriber_user_id;
            
            static::deleted(function () use ($authorId, $subscriberId) {
                self::withoutEvents(function () use ($authorId, $subscriberId) {
                    self::where('subscriber_user_id', $authorId)
                        ->where('author_user_id', $subscriberId)
                        ->delete();
                });
            });
        });
    }
}