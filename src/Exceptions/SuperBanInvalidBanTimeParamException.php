<?php

namespace Harmlessprince\SuperBan\Exceptions;

use InvalidArgumentException;

class SuperBanInvalidBanTimeParamException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct("Invalid ban time provided, ban time should be an int and greater than 1");
    }
}