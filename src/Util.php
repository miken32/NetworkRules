<?php

namespace Miken32\Validation\Network;

use InvalidArgumentException;

class Util
{
    public const IPV4_RANGE_MIN = 0;
    public const IPV4_RANGE_MAX = 32;
    public const IPV6_RANGE_MIN = 0;
    public const IPV6_RANGE_MAX = 128;

    public static function validRange(int $value, int $low, int $high): bool
    {
        return filter_var(
            $value,
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => $low, "max_range" => $high]]
        );
    }

    public static function validIp4Address(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }

    public static function validIp6Address(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }

    public static function validIpAddress(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6);
    }

    public static function validIp4PrivateAddress(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
            && !filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
    }

    public static function validIp6PrivateAddress(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)
            && !filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
    }

    public static function validIpPrivateAddress(string $value): bool
    {
        return !filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
    }

    public static function validIp4Subnet(
        string $value,
        int $low = self::IPV4_RANGE_MIN,
        int $high = self::IPV4_RANGE_MAX
    ): bool
    {
        if (!str_contains($value, '/')) {
            return false;
        }
        [$network, $mask] = explode('/', $value);

        return self::validIp4Address($network) && self::validRange($mask, $low, $high);
    }

    public static function validIp6Subnet(
        string $value,
        int $low = self::IPV6_RANGE_MIN,
        int $high = self::IPV6_RANGE_MAX
    ): bool
    {
        if (!str_contains($value, '/')) {
            return false;
        }
        [$network, $mask] = explode('/', $value);

        return self::validIp6Address($network) && self::validRange($mask, $low, $high);
    }

    public static function validIpSubnet(
        string $value,
        int $low = self::IPV6_RANGE_MIN,
        int $high = self::IPV6_RANGE_MAX
    ): bool
    {
        if (!str_contains($value, '/')) {
            return false;
        }

        [$network, $mask] = explode('/', $value);

        if (!self::validIp6Address($network)) {
            $high = min($high, self::IPV4_RANGE_MAX);
        }
        if ($high > self::IPV6_RANGE_MAX  || $low > $high || $low < 0) {
            throw new InvalidArgumentException('Invalid subnet validation rule arguments');
        }

        return self::validIpAddress($network)
            && self::validRange($mask, $low, $high);
    }

    public static function addressWithinSubnet(string $address, string $subnet): bool
    {
        [$network, $bits] = explode('/', $subnet);
        if (
            self::validIp4Address($address)
            && self::validIp4Subnet($subnet)
        ) {
            // working with ints
            $address = ip2long($address);
            $network = ip2long($network);
            $mask = -1 << (32 - $bits);
        } elseif (
            self::validIp6Address($address)
            && self::validIp6Subnet($subnet)
        ) {
            // working with binary strings
            $address = inet_pton($address);
            $network = inet_pton($network);
            $mask = str_repeat('f', $bits / 4) . match($bits % 4) {
                0 => '',
                1 => '8',
                2 => 'c',
                3 => 'e',
            };
            $mask = pack('H*', str_pad($mask, 32, '0'));
        } else {
            return false;
        }

        return ($address & $mask) === ($network & $mask);
    }
}
