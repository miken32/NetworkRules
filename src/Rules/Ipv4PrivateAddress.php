<?php

namespace Miken32\Validation\Network\Rules;

use Miken32\Validation\Network\Util;

class Ipv4PrivateAddress extends BaseRule
{
    public function doValidation(string $value, ...$parameters): bool
    {
        return Util::validIp4PrivateAddress($value);
    }

    public function message(): string
    {
        return __('The :attribute field must be a valid private IPv4 address');
    }
}
