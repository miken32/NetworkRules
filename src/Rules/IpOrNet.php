<?php

namespace Miken32\Validation\Network\Rules;

use Miken32\Validation\Network\Util;

class IpOrNet extends BaseRule
{
    public function doValidation(string $attribute, string $value, ...$parameters): bool
    {
        return Util::validIPAddress($value) || Util::validIPNetwork($value);
    }

    public function message(): string
    {
        return __("The :attribute field must be an IP address, or a network in CIDR notation");
    }
}
