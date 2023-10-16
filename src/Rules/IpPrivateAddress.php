<?php

namespace Miken32\Validation\Network\Rules;

use Miken32\Validation\Network\Util;

class IpPrivateAddress extends BaseRule
{
    public function doValidation(string $value, ...$parameters): bool
    {
        return Util::validIpPrivateAddress($value);
    }

    public function message(): string
    {
        return __("The :attribute field must be a valid private IP address");
    }
}
