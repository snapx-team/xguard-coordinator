<?php

namespace Xguard\Coordinator;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Xguard\Coordinator\Commands\CreateAdmin;
use Xguard\Coordinator\Http\Middleware\CheckHasAccess;

class CoordinatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function register()
    {
        $this->app->make('Xguard\Coordinator\Http\Controllers\AppController');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Xguard\Coordinator');
        $this->mergeConfigFrom(__DIR__.'/../config.php', 'coordinator_app');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {
        app('router')->aliasMiddleware('coordinator_app_role_check', CheckHasAccess::class);
        $this->loadMigrationsFrom(__DIR__.'/Http/Middleware');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadMigrationsFrom(__DIR__.'/database/seeds');
        $this->loadFactoriesFrom(__DIR__.'/database/factories');


        $this->commands([CreateAdmin::class]);

        include __DIR__.'/routes/web.php';
        include __DIR__.'/routes/api.php';

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/xguard-coordinator'),
        ], 'coordinator-assets');

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            //$schedule->command(DeleteExcessDataPoints::class)->daily(); // TODO: After creating logic to cluster points and save pertinent data, delete all geolocation pings
        });
    }
}
