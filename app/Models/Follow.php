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

    public $timestamps = false; // Используем кастомное created_at

    /**
     * Правила валидации
     */
    public static function rules()
    {
        return [
            'author_user_id' => 'required|exists:users,id|different:subscriber_user_id'
        ];
    }

    /**
     * Отношение к подписчику (тот, кто подписывается)
     */
    public function subscriber()
    {
        return $this->belongsTo(User::class, 'subscriber_user_id');
    }

    /**
     * Отношение к автору (тот, на кого подписываются)
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_user_id');
    }

    /**
     * Проверка существования подписки
     */
    public static function existsSubscription($subscriberId, $authorId)
    {
        return self::where('subscriber_user_id', $subscriberId)
                  ->where('author_user_id', $authorId)
                  ->exists();
    }

    /**
     * Подписаться на пользователя
     */
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

    /**
     * Отписаться от пользователя
     */
    public static function unsubscribe($subscriberId, $authorId)
    {
        return self::where('subscriber_user_id', $subscriberId)
                  ->where('author_user_id', $authorId)
                  ->delete();
    }

    /**
     * Получить всех подписчиков пользователя
     */
    public static function getFollowers($userId)
    {
        return self::where('author_user_id', $userId)
                  ->with('subscriber')
                  ->get()
                  ->pluck('subscriber');
    }

    /**
     * Получить все подписки пользователя
     */
    public static function getSubscriptions($userId)
    {
        return self::where('subscriber_user_id', $userId)
                  ->with('author')
                  ->get()
                  ->pluck('author');
    }

    /**
     * Проверка подписан ли пользователь на другого
     */
    public static function isFollowing($subscriberId, $authorId)
    {
        return self::where('subscriber_user_id', $subscriberId)
                  ->where('author_user_id', $authorId)
                  ->exists();
    }
}