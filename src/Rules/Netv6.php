<?php

namespace Miken32\Validation\Network\Rules;

use Miken32\Validation\Network\Util;

class Netv6 extends BaseNetworkRule
{
    public function doValidation(string $attribute, string $value, ...$parameters): bool
    {
        if ($this->extended) {
            // called by string method
            $this->setBitMaskFromParameters($parameters);
        }

        return Util::validIPv6Network($value, $this->minBits, $this->maxBits);
    }

    public function message(): string
    {
        $message = __('The :attribute field must be an IPv6 network in CIDR notation');
        if (!$this->extended) {
            $message = $this->replace(
                $message,
                "",
                "",
                ["$this->minBits", "$this->maxBits"]
            );
        }

        return $message;
    }

    public function replace(string $message, string $attribute, string $rule, array $parameters): string
    {
        if (count($parameters) === 2) {
            $message .= vsprintf(__(' with a mask between %d and %d bits'), $parameters);
        }

        return $message;
    }
}
