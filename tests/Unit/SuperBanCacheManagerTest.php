<?php

namespace Harmlessprince\SuperBan\Tests\Unit;

use Harmlessprince\SuperBan\SuperBanCacheManager;
use Harmlessprince\SuperBan\Tests\BaseTestCase;
use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\CacheManager;
use Illuminate\Cache\Repository;

class SuperBanCacheManagerTest extends BaseTestCase
{

    private SuperBanCacheManager $repository;

    public function setUp(): void
    {
        parent::setUp();

        // Create an instance of the SuperBanCacheRepository with an ArrayStore for testing
        $this->repository = new SuperBanCacheManager($this->app);
    }


    public function test_it_is_an_instance_of_cache_manager()
    {
        $this->assertInstanceOf(CacheManager::class, $this->repository);
    }

}