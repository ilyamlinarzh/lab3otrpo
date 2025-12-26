<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\OlympicGame;
use App\Models\Comment;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Comment' => 'App\Policies\CommentPolicy',
        'App\Models\OlympicGame' => 'App\Policies\OlympicGamePolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (! $this->app->routesAreCached()) {
            Passport::routes();
            
            Passport::tokensExpireIn(now()->addDays(15));
            Passport::refreshTokensExpireIn(now()->addDays(30));
        }

        Gate::before(function ($user, $ability) {
            if (!$user) {
                return false;
            }

            if ($user && $user->is_admin) {
                return true;
            }
        });

        Gate::define('edit-game', function ($user, OlympicGame $game) {
            return $user->id === $game->user_id;
        });

        Gate::define('delete-game', function ($user, OlympicGame $game) {
            return $user->id === $game->user_id;
        });

        Gate::define('admin', function ($user) {
            return $user && $user->is_admin;
        });

        Gate::define('delete-comment', function ($user, Comment $comment) {
            return $user->id === $comment->user_id;
        });
    }
}
