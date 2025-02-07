<?php

namespace Miken32\Validation\Network\Rules;

use Miken32\Validation\Network\Util;

class RoutableIpOrNet extends BaseRule
{
    public function doValidation(string $attribute, string $value, ...$parameters): bool
    {
        return Util::validRoutableIPAddress($value) || Util::validRoutableIPNetwork($value);
    }

    public function message(): string
    {
        return __("The :attribute field must be a routable IP address or IP network in CIDR notation");
    }
}
