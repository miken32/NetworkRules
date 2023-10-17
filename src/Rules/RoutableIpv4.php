<?php

namespace Miken32\Validation\Network\Rules;

use Miken32\Validation\Network\Util;

class RoutableIpv4 extends BaseRule
{
    public function doValidation(string $value, ...$parameters): bool
    {
        return Util::validRoutableIPv4Address($value);
    }

    public function message(): string
    {
        return __('The :attribute field must be a routable IPv4 address');
    }
}
