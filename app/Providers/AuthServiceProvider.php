<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('edit-user', function ($user, $model) {
            return  $user->id == $model->id || $user->isAdmin(); // sqlite rules!
        });

        Gate::define('manage-taskstatus', function ($user) {
            return  $user->isAdmin();
        });

        Gate::define('edit-task', function ($user, $model) {
            return  $user->id == $model->creator_id || $user->isAdmin(); // sqlite rules!
        });
    }
}
