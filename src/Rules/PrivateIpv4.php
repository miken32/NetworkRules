<?php

namespace Miken32\Validation\Network\Rules;

use Miken32\Validation\Network\Util;

class PrivateIpv4 extends BaseRule
{
    public function doValidation(string $attribute, string $value, ...$parameters): bool
    {
        return Util::validPrivateIPv4Address($value);
    }

    public function message(): string
    {
        return __('The :attribute field must be a private IPv4 address');
    }
}
