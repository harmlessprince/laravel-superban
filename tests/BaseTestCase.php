<?php

namespace Harmlessprince\SuperBan\Tests;

use Harmlessprince\SuperBan\Http\Middleware\SuperBanMiddleware;
use Harmlessprince\SuperBan\SuperBanServiceProvider;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use \Orchestra\Testbench\TestCase as OrchestraTestCase;

class BaseTestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        set_error_handler([$this, 'customErrorHandler']);
        $this->setUpDummyRoutes();
    }
    protected function tearDown(): void
    {
        Cache::flush();
        restore_error_handler();
        parent::tearDown();

    }

    protected function setUpDummyRoutes()
    {
        $this->app['router']->group(
            ['middleware' => SuperBanMiddleware::class . ':0,1,1'],
            function () {
                $this->app['router']->get('use-invalid-param', function () {
                    return 'Hello world!';
                });
            }
        );

        $this->app['router']->group(
            ['middleware' => SuperBanMiddleware::class. ':2,2,2'],
            function () {
                $this->app['router']->get('next-middleware-called', function () {
                    return 'Hello world!';
                });
            }
        );

        $this->app['router']->group(
            ['middleware' => SuperBanMiddleware::class. ':2,2,2'],
            function () {
                $this->app['router']->get('ban/client', function () {
                    return 'Hello world!';
                });
            }
        );

        $this->app['router']->group(
            ['middleware' => SuperBanMiddleware::class. ':2,2,2'],
            function () {
                $this->app['router']->get('too/many/request', function () {
                    return 'Hello world!';
                });
            }
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            SuperBanServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
    public function customErrorHandler($errno, $errstr, $errfile, $errline): void {
        // Your custom error handling logic
        // ...
    }
}