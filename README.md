
# Superban Package

<p>
    SuperBan is a Laravel middleware package that helps you manage and control the rate of incoming requests to your application. It can be useful in scenarios where you want to prevent abuse, limit the number of requests from a specific route or group of route the client and enforce temporary bans when necessary.

</p>

<p>
The package is designed using the <em>Token Bucket Algorithm</em>. 

Below is a brief explanation of the algorithm from Google

`The token bucket is an algorithm used in packet-switched and telecommunications networks. It can be used to check that data transmissions, in the form of packets, conform to defined limits on bandwidth and burstiness (a measure of the unevenness or variations in the traffic flow).
`

In the concept of this Superban package, below is a brief explanation of how it works.

The middleware has a first parameter called maximumRequest. This maximum request parameter represents our tokens and is associated with a key in the cache. So each incoming request into our server consumes a token; if no tokens are left, the request is rejected. If the client tries to hit the same endpoint again within the interval specified for the token to be used, the client is then banned from the specific route for the duration of the ban time supplied.

`Note: All supplied parameters are expected to be in minutes.`

</p>

## Installation
```bash
composer require harmlessprince/superban
```

Once the Superban package is installed, the package will be autoloaded, this package is built with php 8.1. Any Laravel version that supports PHP 8.1 will autoload the package; however, if you want to add it yourself.

Open up `config/app.php` and add the following to the `providers` key.

```bash
'providers' => [
    // other service providers
    Harmlessprince\SuperBan\SuperBanServiceProvider::class,
],
```

## Configuration
The default cache driver is `file`; you can change it to any laravel supported cache driver in your environment variable like below

```dotenv
SUPER_BAN_CACHE_DRIVER=redis
```

You can publish the configuration file using this command:

```bash
php artisan vendor:publish --provider="Harmlessprince\SuperBan\SuperBanServiceProvider" --tag=superban-config
```

A configuration-file named `superban.php` with  defaults will be placed in your `config` directory:

```php

<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Default Cache-Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library in superban. This connection is used when another is
    | not explicitly specified when executing a given caching function within the superban        libary.
    |
    | ** All laravel cache stores are supported **
    |
    */
    'cache_manager' => env('SUPER_BAN_CACHE_DRIVER', 'file'),

    /*
   |--------------------------------------------------------------------------
   | Default Request Key
   |--------------------------------------------------------------------------
   |
   | This option controls the default request key that gets used to
   | identify the client accessing your server.
   | Supported request keys are: "id"(if user is authenticated), "email"(if user is              authenticated), "ip"
   |
   |
   */
    'default_request_key' => env('SUPER_BAN_DEFAULT_REQUEST_KEY', 'ip'),
];
```

## Usage
<p>
To use the SuperBan middleware in your routes, you can apply it like any other middleware. For example, in your routes file or controller:

You can customize the SuperBan behaviour by providing additional parameters:
</p>

<ul>
    <li>

**Maximum Requests:** The maximum number of requests allowed in the specified interval      (default: 200).

  </li>
   <li>

**Interval in Minutes:** The time window (in minutes) during which the maximum requests are allowed (default: 2 minutes).

  </li>
     <li>

**Ban Time in Minutes:** The duration (in minutes) a client is banned if they exceed the maximum requests (default: 1440 minutes or 24 hours).

  </li>
</ul>

```php


Route::middleware('superban')->get('/user', function (Request $request) {
     return ["hello" => 'World'];
});



Route::get('/jane', function (Request $request) {
    return ["hello" => 'World'];
})->middleware('superban:200,2,60');


Route::middleware(['superban:200,2,1440])->group(function () {
   Route::post('/thisroute', function () {
       // ...
   });
 
   Route::post('anotherroute', function () {
       // ...
   });
});
```

## Exceptions

<ul>
    <li>

**`SuperBanClientBannedException`**: Thrown when a client is banned.

  </li>
  <li>

**`SuperBanTooManyRequestException`**: Thrown when a client exceeds the maximum allowed requests.

  </li>
  <li>

**`SuperBanInvalidMaxRequestParamException`**: This exception is thrown when the maximum requests parameter supplied method is either not a numeric value or is not a positive integer.

  </li>

  <li>

**`SuperBanInvalidIntervalParamException`**: This exception is thrown when the interval  parameter supplied method is either not a numeric value or is not a positive integer.
  </li>

 <li>

**`SuperBanInvalidBanTimeParamException`**: This exception is thrown when the ban time parameter provided is either not a numeric value or is not a positive integer.
  </li>
</ul>

### Extra
<p>
When you use the middlware in your route, the package adds some headers to every response going out of the application like below 
</p>

```bash
X-Superban-Ratelimit-Limit: 2
X-Superban-Ratelimit-Remaining: 0
X-Superban-Retry-After: 18
```
The response header below implies the client has excided the maximum number of requests and can try again in 18 seconds.
### Testing

```bash
composer test
```
Test Result Should Look Like This

```
Super Ban Cache Manager (Harmlessprince\SuperBan\Tests\Unit\SuperBanCacheManager)
 ✔ It is an instance of cache manager

Super Ban Middleware (Harmlessprince\SuperBan\Tests\Feature\SuperBanMiddleware)
 ✔ Invalid param throws an internal server error
 ✔ Valid param return goes to next middleware
 ✔ Client get too many after exhausting max request
 ✔ Client first too many request then get they have banned

Super Ban Service (Harmlessprince\SuperBan\Tests\Unit\SuperBanService)
 ✔ Invalid max requests param
 ✔ Invalid interval param
 ✔ Invalid ban time param
 ✔ Non umeric values params
 ✔ Zero max requests param
 ✔ Zero interval param
 ✔ Zero ban time param

Super Ban Service Provider (Harmlessprince\SuperBan\Tests\Unit\SuperBanServiceProvider)
 ✔ It registers config
 ✔ It binds superban cache repository

OK (14 tests, 19 assertions)

```

## Author
Name: Adewuyi Taofeeq <br>
Email: realolamilekan@gmail.com <br>
LinkenIn:  <a href="#license">Adewuyi Taofeeq Olamikean</a> <br>
## License
MIT
