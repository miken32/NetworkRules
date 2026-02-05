<?php

namespace Miken32\Validation\Network\Rules;

use InvalidArgumentException;
use Miken32\Validation\Network\Util;

abstract class BaseNetworkRule extends BaseRule
{
    /** @var int<Util::IPV6_RANGE_MIN,Util::IPV6_RANGE_MAX> $minBits the user-set minimum */
    protected int $minBits;

    /** @var int<Util::IPV6_RANGE_MIN,Util::IPV6_RANGE_MAX> $maxBits the user-set maximium */
    protected int $maxBits;

    /** @var 0 the actual minimum */
    protected int $absMin = Util::IPV6_RANGE_MIN;

    /** @var 32|128 the actual maximum, override for IPv4 */
    protected int $absMax = Util::IPV6_RANGE_MAX;

    /**
     * @param int|null $low
     * @param int|null $high
     */
    public function __construct(?int $low = null, ?int $high = null)
    {
        $this->setBitMask(
            $low ?? $this->absMin,
            $high ?? $this->absMax,
        );
    }

    /**
     * @param int $low
     * @param int $high
     * @return void
     */
    protected function setBitMask(int $low, int $high): void
    {
        if ($low < $this->absMin || $high > $this->absMax || $low > $high) {
            throw new InvalidArgumentException();
        }

        $this->minBits = $low;
        $this->maxBits = $high;
    }

    /**
     * @param string[]|int[] $parameters
     * @return void
     */
    protected function setBitMaskFromParameters(array $parameters): void
    {
        $low = (int)($parameters[0] ?? $this->absMin);
        $high = (int)($parameters[1] ?? $this->absMax);

        $this->setBitMask($low, $high);
    }
}