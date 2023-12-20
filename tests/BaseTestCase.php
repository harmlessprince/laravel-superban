<?php

namespace Harmlessprince\SuperBan\Tests;

use Harmlessprince\SuperBan\Http\Middleware\SuperBanMiddleware;
use Harmlessprince\SuperBan\SuperBanServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use \Orchestra\Testbench\TestCase as OrchestraTestCase;

class BaseTestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpDummyRoutes();
    }
    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();

    }

    protected function setUpDummyRoutes()
    {
        $this->app['router']->group(
            ['middleware' => SuperBanMiddleware::class . ':0,1,1,default'],
            function () {
                $this->app['router']->get('use-invalid-param', function () {
                    return 'Hello world!';
                });
            }
        );

        $this->app['router']->group(
            ['middleware' => SuperBanMiddleware::class. ':2,2,2,valid_key'],
            function () {
                $this->app['router']->get('next-middleware-called', function () {
                    return 'Hello world!';
                });
            }
        );

        $this->app['router']->group(
            ['middleware' => SuperBanMiddleware::class. ':2,2,2,valid_key'],
            function () {
                $this->app['router']->get('ban/client', function () {
                    return 'Hello world!';
                });
            }
        );

        $this->app['router']->group(
            ['middleware' => SuperBanMiddleware::class. ':2,2,2,valid_key'],
            function () {
                $this->app['router']->get('too/many/request', function () {
                    return 'Hello world!';
                });
            }
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            SuperBanServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}