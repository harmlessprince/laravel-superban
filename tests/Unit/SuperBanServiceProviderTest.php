<?php

namespace Harmlessprince\SuperBan\Tests\Unit;


use Harmlessprince\SuperBan\SuperBanCacheManager;
use Harmlessprince\SuperBan\Tests\BaseTestCase;
use Illuminate\Cache\CacheManager;

class SuperBanServiceProviderTest extends BaseTestCase
{

    public function test_it_registers_config()
    {
        $config = $this->app['config']->get('superban');

        $this->assertNotNull($config);
    }


    public function test_it_binds_superban_cache_repository()
    {
        $repository = $this->app->make(SuperBanCacheManager::class);

        $this->assertInstanceOf(CacheManager::class, $repository);
    }


}