<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        /* Blade::directive('projectName', function () {
            return 'Task Manager @ Laravel';
        }); */

        if (env('APP_HTTPS')) {
            $url->forceSchema('https');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('production')) {
            $this->app->register(\Rollbar\Laravel\RollbarServiceProvider::class);
        }

        if ($this->app->environment(['local', 'testing'])) {
            $this->app->register(\Laravel\Dusk\DuskServiceProvider::class);
        }
    }
}
