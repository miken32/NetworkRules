<?php

namespace Miken32\Validation\Network\Rules;

use Miken32\Validation\Network\Util;

class RoutableIpv6 extends BaseRule
{
    public function doValidation(string $value, ...$parameters): bool
    {
        return Util::validRoutableIPv6Address($value);
    }

    public function message(): string
    {
        return __('The :attribute field must be a routable IPv6 address');
    }
}
