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

    public function doValidation(string $value, ...$parameters): bool
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
            throw new InvalidArgumentException('Invalid subnet validation rule arguments');
        }

        $result = Util::validIp6Subnet($value, $this->minBits, $this->maxBits);
        if (!$result && str_contains($value, '/')) {
            [, $mask] = explode('/', $value);
            $this->validMask = Util::validRange($mask, $this->minBits, $this->maxBits);

            return false;
        }

        return $result;
    }

    public function message(): string
    {
        if (!$this->validMask) {
            // this only works when validating with instance method
            return sprintf(
                __('The :attribute field must have a network mask between %d and %d bits'),
                $this->minBits,
                $this->maxBits
            );
        }

        return __('The :attribute field must be a valid IPv6 subnet in CIDR notation');
    }
}