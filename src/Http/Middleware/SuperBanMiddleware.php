<?php

namespace Harmlessprince\SuperBan\Http\Middleware;


use Closure;
use Harmlessprince\SuperBan\Exceptions\SuperBanClientBannedException;
use Harmlessprince\SuperBan\Exceptions\SuperBanTooManyRequestException;
use Harmlessprince\SuperBan\SuperBanCacheManager;
use Harmlessprince\SuperBan\SuperBanService;
use Illuminate\Http\Request;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class SuperBanMiddleware
{

    const KEY_LAST_RESET_TIME_SUFFIX = '_last_reset_time_superban';
    const KEY_COUNTER_SUFFIX = '_counter_superban';
    const KEY_BAN_TIME_SUFFIX = '_ban_time_superban';

    protected SuperBanCacheManager $cacheManager;
    protected SuperBanService $service;

    public function __construct(SuperBanCacheManager $cacheManager, SuperBanService $service)
    {

        $this->cacheManager = $cacheManager;
        $this->service = $service;
    }


    /**
     * @throws InvalidArgumentException
     * @throws SuperBanClientBannedException
     * @throws SuperBanTooManyRequestException
     */
    public function handle(Request $request, Closure $next, $maximumRequests = 200, $intervalInMinutes = 2, $banTimeMinutes = 1440): Response
    {

        $this->service->validateParameters($maximumRequests, $intervalInMinutes, $banTimeMinutes);

        $key = $this->service->resolveRequestSignature($request);
        $intervalInSeconds = $intervalInMinutes * 60;
        $banTimeInSeconds = $banTimeMinutes * 60;

        $keyLastResetTime = $key . self::KEY_LAST_RESET_TIME_SUFFIX;
        $keyCounter = $key . self::KEY_COUNTER_SUFFIX;
        $keyBanTime = $key . self::KEY_BAN_TIME_SUFFIX;


        $banTime = $this->cacheManager->get($keyBanTime);


        if ($banTime && $banTime > time()) {
            $retryAfterInSeconds = $banTime - time();
            // client is banned until the ban time has passed
            throw new SuperBanClientBannedException($retryAfterInSeconds, $maximumRequests);
        }

        $lastResetTime = $this->cacheManager->get($keyLastResetTime);

        // we check if the time window since the last counter reset has expired
        if (time() - $lastResetTime >= $intervalInSeconds) {
            // If elapsed, reset the counter
            $this->cacheManager->put($keyCounter, $maximumRequests, $intervalInSeconds);
            $this->cacheManager->put($keyLastResetTime, time(), $intervalInSeconds);
        }
        // Get the current request count
        $requestLeft = $this->cacheManager->get($keyCounter);


        if ($requestLeft <= 0) {
            // Calculate the time left in seconds
            $timeLeft = $lastResetTime + $intervalInSeconds - time();


            // Ban the user for the specified ban time
            $banTime = time() + $banTimeInSeconds;

            $this->cacheManager->put($keyBanTime, $banTime, $banTimeInSeconds);
            $retryAfter = max(0, $timeLeft);

            throw new SuperBanTooManyRequestException($retryAfter, $maximumRequests);

        }


        // Decrement request count by 1
        $this->cacheManager->decrement($keyCounter);

        // Add headers indicating the number of tokens left
        $response = $next($request);
        $response->header('X-SuperBan-RateLimit-Limit', $maximumRequests);
        $response->header('X-SuperBan-RateLimit-Remaining', $this->service->calculateRemainingAttempts($requestLeft));

        return $response;
    }
}