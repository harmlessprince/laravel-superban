<?php

namespace Harmlessprince\SuperBan\Tests\Feature;

use Harmlessprince\SuperBan\Tests\BaseTestCase;

class SuperBanMiddlewareTest extends BaseTestCase
{
    public function test_invalid_param_throws_internal_server_error(): void
    {
        $this->get('/use-invalid-param')
            ->assertInternalServerError();
    }

    public function test_valid_param_return_goes_to_next_middleware(): void
    {
        $this->get('/next-middleware-called')
            ->assertOk();
    }

    public function test_client_get_too_many_after_exhausting_max_request(): void
    {
        $this->get('too/many/request')
            ->assertOk();
        $this->get('too/many/request')
            ->assertOk();
        $this->get('too/many/request')->assertStatus(429);
    }

    public function test_client_first_too_many_request_then_get_they_have_banned(): void
    {
        $this->get('too/many/request')
            ->assertOk();
        $this->get('too/many/request')
            ->assertOk();

        $this->get('too/many/request')->assertStatus(429);
        $this->get('too/many/request')->assertStatus(401);
    }
}