<?php

namespace Harmlessprince\SuperBan\Tests\Feature;

use Harmlessprince\SuperBan\Exceptions\SuperBanInvalidMaxRequestParamException;
use Harmlessprince\SuperBan\Exceptions\SuperBanTooManyRequestException;
use Harmlessprince\SuperBan\Tests\BaseTestCase;

class SuperBanMiddlewareTest extends BaseTestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        set_exception_handler(null);
    }

    public function test_invalid_param_throws_internal_server_error(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(SuperBanInvalidMaxRequestParamException::class);
        $this->get('/use-invalid-param');

    }

    public function test_valid_param_return_goes_to_next_middleware(): void
    {
        $this->get('/next-middleware-called')
            ->assertOk();
    }

    public function test_client_get_too_many_after_exhausting_max_request(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(SuperBanTooManyRequestException::class);
        $this->get('too/many/request')
            ->assertOk();
        $this->get('too/many/request')
            ->assertOk();
        $this->get('too/many/request');
    }

    public function test_client_first_geet_too_many_request_then_get_they_have_banned(): void
    {
        $this->withoutExceptionHandling();

        $this->expectException(SuperBanTooManyRequestException::class);
        $this->get('too/many/request')
            ->assertOk();
        $this->get('too/many/request')
            ->assertOk();


        $this->get('too/many/request')->assertStatus(429);
//        $this->get('too/many/request')->assertStatus(401);
    }


}