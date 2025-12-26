<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class OlympicGame extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Поля, которые можно массово присваивать
     */
    protected $fillable = [
        'city',
        'year', 
        'title',
        'image_filename',
        'short_description',
        'detailed_description',
        'fun_fact',
        'user_id' => 'required|exists:users,id'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'game_id');
    }

    /**
     * Мутатор для поля year - всегда сохраняем как integer
     */
    public function setYearAttribute($value)
    {
        $this->attributes['year'] = (int) $value;
    }

    /**
     * Мутатор для поля city - убираем лишние пробелы
     */
    public function setCityAttribute($value)
    {
        $this->attributes['city'] = trim($value);
    }

    /**
     * Мутатор для поля title - убираем лишние пробелы
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = trim($value);
    }

    /**
     * Мутатор для created_at - преобразуем в Carbon объект
     */
    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = Carbon::parse($value);
    }

    /**
     * Мутатор для updated_at - преобразуем в Carbon объект
     */
    public function setUpdatedAtAttribute($value)
    {
        $this->attributes['updated_at'] = Carbon::parse($value);
    }

    public function getIsDeletedAttribute()
    {
        return !is_null($this->deleted_at);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($game) {
            if (empty($game->user_id) && Auth::check()) {
                $game->user_id = Auth::id();
            }
            
            if (!Auth::check() || Gate::forUser(Auth::user())->denies('create-olympic-game')) {
                throw new \Exception('Для создания записи необходимо авторизоваться');
            }
            
            return true;
        });

        static::updating(function ($game) {
            if (!is_null($game->deleted_at)) {
                if (!Auth::check() || Gate::forUser(Auth::user())->denies('admin')) {
                    throw new \Exception('Нельзя редактировать удаленную запись');
                }
                return true;
            }
            
            $user = Auth::user();
            if (!$user || Gate::forUser($user)->denies('edit-game', $game)) {
                throw new \Exception('Вы можете редактировать только свои записи');
            }
            
            return true;
        });

        static::deleting(function ($game) {
            $user = Auth::user();
            
            if (!$user || Gate::forUser($user)->denies('delete-game', $game)) {
                throw new \Exception('Вы можете удалять только свои записи');
            }
            
            return true;
        });

        static::restoring(function ($game) {
            $user = Auth::user();
            
            if (!$user || Gate::forUser($user)->denies('admin')) {
                throw new \Exception('Только администратор может восстанавливать удаленные записи');
            }
            
            return true;
        });
    }
}