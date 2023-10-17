<?php

namespace Miken32\Validation\Network\Rules;

use Miken32\Validation\Network\Util;

class PrivateIp extends BaseRule
{
    public function doValidation(string $value, ...$parameters): bool
    {
        return Util::validPrivateIPAddress($value);
    }

    public function message(): string
    {
        return __("The :attribute field must be a private IP address");
    }
}
