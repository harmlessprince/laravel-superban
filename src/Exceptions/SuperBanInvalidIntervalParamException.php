<?php

namespace Harmlessprince\SuperBan\Exceptions;

use InvalidArgumentException;

class SuperBanInvalidIntervalParamException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct("Invalid interval provided, interval should be an int and greater than 1");
    }
}