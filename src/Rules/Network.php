<?php

namespace Miken32\Validation\Network\Rules;

use InvalidArgumentException;
use Miken32\Validation\Network\Util;

class Network extends BaseRule
{
    public function doValidation(string $attribute, string $value, ...$parameters): bool
    {
        return Util::validIPNetwork($value);
    }

    public function message(): string
    {
        $message = __('The :attribute field must be an IPv4 or IPv6 network in CIDR notation');

        return $message;
    }
}
