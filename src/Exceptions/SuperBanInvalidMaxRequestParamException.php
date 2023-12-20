<?php

namespace Harmlessprince\SuperBan\Exceptions;

use InvalidArgumentException;
class SuperBanInvalidMaxRequestParamException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct("Invalid maximum number of requests provided, maximum request should be an int and greater than 1", 500);
    }
}