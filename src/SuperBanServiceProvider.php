<?php

namespace Harmlessprince\SuperBan;
use Harmlessprince\SuperBan\Http\Middleware\SuperBanMiddleware;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class SuperBanServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/superban.php', 'superban');
    }

    /**
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/superban.php' => config_path('superban.php'),
        ], 'config');


        $this->app->bind(SuperBanCacheManager::class, function () {
            return new SuperBanCacheManager($this->app);
        });

        $router = $this->app->make(Router::class);

        $router->aliasMiddleware('superban', SuperBanMiddleware::class);

    }
}