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
       $this->mergeConfig();
    }

    /**
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $this->publishConfigs();

        $this->app->bind(SuperBanCacheManager::class, function () {
            return new SuperBanCacheManager($this->app);
        });

        $router = $this->app->make(Router::class);

        $router->aliasMiddleware('superban', SuperBanMiddleware::class);





    }

    protected function publishConfigs(): void
    {
        $path = $this->getConfigPath();
        $this->publishes([
            $path => config_path('superban.php'),
        ], 'superban-config');
    }

    public function getConfigPath()
    {
        return __DIR__ . '/../config/superban.php';
    }
    private function mergeConfig()
    {
        $path = $this->getConfigPath();
        $this->mergeConfigFrom($path, 'superban');
    }

}