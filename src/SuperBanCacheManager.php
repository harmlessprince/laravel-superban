<?php

namespace Harmlessprince\SuperBan;

use Illuminate\Cache\CacheManager;

class SuperBanCacheManager extends CacheManager
{
    public function getDefaultDriver()
    {
        return $this->app['config']['superban.cache_manager'];
    }
}
