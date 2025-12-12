<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::if('author', function ($model) {
            return auth()->check() && auth()->id() === $model->user_id;
        });

        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->is_admin;
        });

        Blade::if('canManage', function ($model) {
            if (!auth()->check()) {
                return false;
            }
            
            return auth()->id() === $model->user_id || auth()->user()->is_admin;
        });

        Blade::if('softDelete', function ($model) {
            if (!auth()->check()) {
                return false;
            }
            
            return auth()->id() === $model->user_id && !auth()->user()->is_admin;
        });
    }
}