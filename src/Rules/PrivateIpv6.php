<?php

namespace Miken32\Validation\Network\Rules;

use Miken32\Validation\Network\Util;

class PrivateIpv6 extends BaseRule
{
    public function doValidation(string $value, ...$parameters): bool
    {
        return Util::validPrivateIPv6Address($value);
    }

    public function message(): string
    {
        return __('The :attribute field must be a private IPv6 address');
    }
}
