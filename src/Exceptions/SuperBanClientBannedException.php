<?php

namespace Harmlessprince\SuperBan\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class SuperBanClientBannedException extends HttpException
{
    public function __construct($retryAfterInSeconds, $maximumRequests)
    {
        parent::__construct(
             401,
            "You have been banned, retry in " . $retryAfterInSeconds,
            null,
            [
                'X-SuperBan-Retry-After' => $retryAfterInSeconds,
                'X-SuperBan-RateLimit-Limit' => $maximumRequests,
                'X-SuperBan-Ratelimit-Remaining' => 0,
            ]
        );
    }
}