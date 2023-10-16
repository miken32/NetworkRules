<?php

namespace Miken32\Validation\Network\Rules;

use Miken32\Validation\Network\Util;

class IpOrNet extends BaseRule
{
    public function doValidation(string $value, ...$parameters): bool
    {
        return Util::validIpAddress($value) || Util::validIpSubnet($value);
    }

    public function message(): string
    {
        return __("The :attribute field must be a valid IP address, or a subnet in CIDR notation");
    }
}
