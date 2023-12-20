<?php

namespace Harmlessprince\SuperBan\Tests\Unit;

use Harmlessprince\SuperBan\SuperBanCacheManager;
use Harmlessprince\SuperBan\Tests\BaseTestCase;
use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\CacheManager;
use Illuminate\Cache\Repository;

class SuperBanCacheManagerTest extends BaseTestCase
{
    /** @var SuperBanCacheManager */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();

        // Create an instance of the SuperBanCacheRepository with an ArrayStore for testing
        $this->repository = new SuperBanCacheManager($this->app);
    }


    public function test_it_is_an_instance_of_repository()
    {
        $this->assertInstanceOf(CacheManager::class, $this->repository);
    }

}