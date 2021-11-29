<?php

namespace Modules\Membership;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\RouteRegistrar as Router;
use Modules\Membership\MembershipEventServiceProvider;

/**
 * 
 */
class ModulesMembershipServiceProvider extends ServiceProvider
{

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(MembershipEventServiceProvider::class);
    }
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadConfig();
        $this->loadRoutes($router);
        $this->loadMigrationsAndFactories();
        $this->loadViews();
    }

    public function loadConfig()
    {
        $path = __DIR__ . '/../config/membership.php';
        $this->mergeConfigFrom($path, 'membership');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $path => config_path('membership.php'),
            ], 'membership:config');
        }
    }

    public function loadRoutes(Router $router)
    {
        $router->prefix('api')
            ->namespace('Modules\Membership\Http\Controllers\API')
            ->middleware(['api'])
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
            });
    }

    public function loadMigrationsAndFactories()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

    private function loadViews()
    {
        $path = __DIR__ . '/../resources/views';
        $this->loadViewsFrom($path, 'membership');
    }
}
