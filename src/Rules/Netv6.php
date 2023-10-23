<?php

namespace Miken32\Validation\Network\Rules;

use InvalidArgumentException;
use Miken32\Validation\Network\Util;

class Netv6 extends BaseRule
{
    private bool $validMask = true;

    public function __construct(
        private ?int $minBits = Util::IPV6_RANGE_MIN,
        private ?int $maxBits = Util::IPV6_RANGE_MAX
    )
    {
    }

    public function doValidation(string $attribute, string $value, ...$parameters): bool
    {
        if ($this->extended) {
            // called by string method
            $this->minBits = (int)($parameters[0] ?? Util::IPV6_RANGE_MIN);
            $this->maxBits = (int)($parameters[1] ?? Util::IPV6_RANGE_MAX);
        }

        if (
            $this->minBits < 0
            || $this->maxBits > Util::IPV6_RANGE_MAX
            || $this->minBits > $this->maxBits
        ) {
            throw new InvalidArgumentException('Invalid network validation rule arguments');
        }

        $result = Util::validIPv6Network($value, $this->minBits, $this->maxBits);
        if (!$result && str_contains($value, '/')) {
            [, $mask] = explode('/', $value);
            $this->validMask = Util::validRange($mask, $this->minBits, $this->maxBits);

            return false;
        }

        return $result;
    }

    public function message(): string
    {
        $message = __('The :attribute field must be an IPv6 network in CIDR notation');
        if (!$this->extended) {
            $message = $this->replace($message, "", "", [$this->minBits, $this->maxBits]);
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
