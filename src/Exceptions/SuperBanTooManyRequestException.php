<?php

namespace Harmlessprince\SuperBan\Exceptions;


use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class SuperBanTooManyRequestException extends TooManyRequestsHttpException
{
    public function __construct($retryAfterInSeconds, $maximumRequests)
    {
        parent::__construct(
            $retryAfterInSeconds,
            "Too many request, retry in " . $retryAfterInSeconds,
            null,
            429,
            [
                'X-SuperBan-Retry-After' => $retryAfterInSeconds,
                'X-SuperBan-RateLimit-Limit' => $maximumRequests,
                'X-SuperBan-RateLimit-Remaining' => 0,
            ]
        );
    }
}