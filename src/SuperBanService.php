<?php

namespace Harmlessprince\SuperBan;

use Harmlessprince\SuperBan\Exceptions\SuperBanInvalidBanTimeParamException;
use Harmlessprince\SuperBan\Exceptions\SuperBanInvalidIntervalParamException;
use Harmlessprince\SuperBan\Exceptions\SuperBanInvalidMaxRequestParamException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class SuperBanService
{

    public function geDefaultKey(Request $request): ?string
    {
        return $request->ip();
    }


    /**
     * Resolves the request signature based on the provided key or falls back to the default signature.
     *
     * If the key is provided, it is returned as the request signature. Otherwise, the default
     * request signature is generated based on the configured default key in the `superban` package.
     *
     * @param Request $request An instance of the `Illuminate\Http\Request` class representing the incoming HTTP request.
     * @return string The resolved request signature.
     */
    public function resolveRequestSignature(Request $request): string
    {
        return $this->requestSignature($request);
    }

    /**
     * Calculates the remaining attempts based on the number of requests left.
     *
     *
     * @param int $requestLeft The number of requests left before reaching the maximum limit.
     *
     * @return int The calculated remaining attempts (minimum value is 0).
     */
    public function calculateRemainingAttempts(int $requestLeft): int
    {
        return max(0, $requestLeft - 1);
    }

    /**
     * Generates a default signature for a given HTTP request.
     *
     * The signature is determined based on the configured default key in the `superban` package.
     * This signature is often used for identifying users or clients making requests.
     *
     * @param Request $request An instance of the `Illuminate\Http\Request` class representing the incoming HTTP request.
     *
     * @return string An encrypted string representing the signature for the given request.
     */
    protected function requestSignature(Request $request): string
    {
        $defaultKey = config('superban.default_request_key');

        if ($defaultKey == 'email' && $request->user()) {
            $defaultKey = $request->user()->email;
        } elseif ($defaultKey == 'id' && $request->user()) {
            $defaultKey = $request->user()->getAuthIdentifier();
        } else {
            $defaultKey = $request->getClientIp();
        }

        //for the key to be unique for each request
        return $this->encrypt($request->method() . $request->url() . $defaultKey);
    }

    protected function encrypt(string $data): string
    {
        // Use your preferred encryption method here
        return Crypt::encryptString($data);
    }

    /**
     * Validates the parameters used in the configuration of rate limiting.
     *
     * This method is called before applying rate limiting to ensure that the parameters
     * are of the correct numeric format.
     *
     * @param int $maxRequests The maximum number of requests allowed within the specified interval.
     * @param int $interval The time interval (in minutes) during which the maximum number of requests is allowed.
     * @param int $banTime The time (in minutes) for which a user or client is banned after exceeding the maximum allowed requests.
     *
     * @throws SuperBanInvalidMaxRequestParamException If `$maxRequests` is not a numeric value.
     * @throws SuperBanInvalidIntervalParamException If `$interval` is not a numeric value.
     * @throws SuperBanInvalidBanTimeParamException If `$banTime` is not a numeric value.
     */
    public function validateParameters($maxRequests, $interval, $banTime): void
    {

        $this->validateNumericParameter($maxRequests, new SuperBanInvalidMaxRequestParamException());
        $this->validateNumericParameter($interval, new SuperBanInvalidIntervalParamException());
        $this->validateNumericParameter($banTime, new SuperBanInvalidBanTimeParamException());

        $this->validatePositiveParameter($maxRequests, new SuperBanInvalidMaxRequestParamException());
        $this->validatePositiveParameter($interval, new SuperBanInvalidIntervalParamException());
        $this->validatePositiveParameter($banTime, new SuperBanInvalidBanTimeParamException());

    }

    private function validateNumericParameter($value, $exception): void
    {
        if (!is_numeric($value)) {
            throw new $exception;
        }
    }

    private function validatePositiveParameter($value, $exception): void
    {
        if ($value < 1) {
            throw new $exception;
        }
    }
}