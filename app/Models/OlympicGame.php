<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

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
}