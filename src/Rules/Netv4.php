<?php

namespace Miken32\Validation\Network\Rules;

use InvalidArgumentException;
use Miken32\Validation\Network\Util;

class Netv4 extends BaseRule
{
    private bool $validMask = true;

    public function __construct(
        private ?int $minBits = Util::IPV4_RANGE_MIN,
        private ?int $maxBits = Util::IPV4_RANGE_MAX
    )
    {
    }

    public function doValidation(string $value, ...$parameters): bool
    {
        if ($this->extended) {
            // called by string method
            $this->minBits = (int)($parameters[0] ?? Util::IPV4_RANGE_MIN);
            $this->maxBits = (int)($parameters[1] ?? Util::IPV4_RANGE_MAX);
        }

        if (
            $this->minBits < 0
            || $this->maxBits > Util::IPV4_RANGE_MAX
            || $this->minBits > $this->maxBits
        ) {
            throw new InvalidArgumentException('Invalid network validation rule arguments');
        }

        $result = Util::validIPv4Network($value, $this->minBits, $this->maxBits);
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

        return __('The :attribute field must be an IPv4 network in CIDR notation');
    }
}