<?php

namespace Miken32\Validation\Network\Rules;

use Miken32\Validation\Network\Util;

class PrivateNet extends BaseRule
{
    public function doValidation(string $attribute, string $value, ...$parameters): bool
    {
        return Util::validPrivateIPNetwork($value);
    }

    public function message(): string
    {
        return __("The :attribute field must be a private IP network");
    }
}
