<?php

namespace Miken32\Validation\Network\Rules;

use Miken32\Validation\Network\Util;

class RoutableNet extends BaseRule
{
    public function doValidation(string $value, ...$parameters): bool
    {
        return Util::validRoutableIPNetwork($value);
    }

    public function message(): string
    {
        return __("The :attribute field must be a routable IP network in CIDR notation");
    }
}
