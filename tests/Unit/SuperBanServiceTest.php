<?php

namespace Harmlessprince\SuperBan\Tests\Unit;

use Harmlessprince\SuperBan\Exceptions\SuperBanInvalidBanTimeParamException;
use Harmlessprince\SuperBan\Exceptions\SuperBanInvalidIntervalParamException;
use Harmlessprince\SuperBan\Exceptions\SuperBanInvalidMaxRequestParamException;
use Harmlessprince\SuperBan\SuperBanService;
use Harmlessprince\SuperBan\Tests\BaseTestCase;
use Illuminate\Contracts\Container\BindingResolutionException;

class SuperBanServiceTest extends BaseTestCase
{

    protected SuperBanService $banService;

    /**
     * @throws BindingResolutionException
     */
    public function setUp(): void
    {
        parent::setUp();
        set_error_handler([$this, 'customErrorHandler']);
        $this->banService = app()->make(SuperBanService::class);
    }


    public function test_invalid_max_requests_param()
    {
        $this->withoutExceptionHandling();
        $this->expectException(SuperBanInvalidMaxRequestParamException::class);
        $this->banService->validateParameters(0, 2, 3);
    }

    public function test_invalid_interval_param()
    {
        $this->withoutExceptionHandling();
        $this->expectException(SuperBanInvalidIntervalParamException::class);
        $this->banService->validateParameters(200, 'invalid', 1440);
    }

    public function test_invalid_ban_time_param()
    {
        $this->withoutExceptionHandling();
        $this->expectException(SuperBanInvalidBanTimeParamException::class);
        $this->banService->validateParameters(200, 2, 'invalid');
    }

    public function test_non_umeric_values_params()
    {
        $this->withoutExceptionHandling();
        $this->expectException(SuperBanInvalidMaxRequestParamException::class);
        $this->banService->validateParameters('invalid', 'invalid', 'invalid');
    }

    public function test_zero_max_requests_param()
    {
        $this->withoutExceptionHandling();
        $this->expectException(SuperBanInvalidMaxRequestParamException::class);
        $this->banService->validateParameters(0, 2, 1440);
    }

    public function test_zero_interval_param()
    {
        $this->withoutExceptionHandling();
        $this->expectException(SuperBanInvalidIntervalParamException::class);
        $this->banService->validateParameters(200, 0, 1440);
    }

    public function test_zero_ban_time_param()
    {
        $this->withoutExceptionHandling();
        $this->expectException(SuperBanInvalidBanTimeParamException::class);
        $this->banService->validateParameters(200, 2, 0);
    }

}