<?php
/*
 * This file is part of the Laravel superban package.
 *
 * (c) Adewuyi taofeeq <realolamilekan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
return [
    'cache_manager' => env('SUPERBAN_CACHE_DRIVER', 'file'),
    'default_request_key' => env('SUPER_BAN_DEFAULT_REQUEST_KEY', 'ip'),
];