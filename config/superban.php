<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library in superban. This connection is used when another is
    | not explicitly specified when executing a given caching function within the superban libary.
    |
    | ** All laravel cache stores are supported **
    |
    */
    'cache_manager' => env('SUPERBAN_CACHE_DRIVER', 'file'),

    /*
   |--------------------------------------------------------------------------
   | Default Request Key
   |--------------------------------------------------------------------------
   |
   | This option controls the default request key that gets used to
   | identify the client accessing your server.
   | Supported request keys are: "id"(if user is authenticated), "email"(if user is authenticated), "ip"
   |
   |
   */
    'default_request_key' => env('SUPER_BAN_DEFAULT_REQUEST_KEY', 'ip'),
];