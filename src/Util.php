<?php

namespace Miken32\Validation\Network;

use InvalidArgumentException;
use Throwable;

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
        ) !== false;
    }

    public static function validIPv4Address(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
    }

    public static function validIPv6Address(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
    }

    public static function validIPAddress(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6) !== false;
    }

    public static function validPrivateIPv4Address(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false
            && filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) === false;
    }

    public static function validPrivateIPv6Address(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false
            && filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) === false;
    }

    public static function validPrivateIPAddress(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) === false;
    }

    public static function validPrivateIPNetwork(string $value): bool
    {
        if (self::validIPv4Network($value)) {
            [$net, $mask] = explode('/', $value);
            return match(true) {
                self::addressWithinNetwork($net, '10.0.0.0/8') => $mask >= 8,
                self::addressWithinNetwork($net, '172.16.0.0/12') => $mask >= 12,
                self::addressWithinNetwork($net, '192.168.0.0/16') => $mask >= 16,
                default => false
            };
        }
        if (self::validIPv6Network($value)) {
            [$net, $mask] = explode('/', $value);
            if (self::validPrivateIPv6Address($net)) {
                // fc00::/7 is the only private range
                return $mask >= 7;
            }
        }

        return false;
    }

    public static function validRoutableIPv4Address(string $value): bool
    {
        $priv_res = filter_var(
            $value,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) !== false;
        if (!$priv_res) {
            return false;
        }
        // implementing the same restrictions as PHP 8.2's FILTER_FLAG_GLOBAL_RANGE
        $ip = explode('.', $value);

        return !(
            ($ip[0] === '100' && $ip[1] >= 64 && $ip[1] <= 127)
            || ($ip[0] === '192' && $ip[1] === '0' && $ip[2] === '0')
            || ($ip[0] === '192' && $ip[1] === '0' && $ip[2] === '2')
            || ($ip[0] === '198' && $ip[1] >= 18 && $ip[1] <= 19)
            || ($ip[0] === '198' && $ip[1] === '51' && $ip[2] === '100')
            || ($ip[0] === '203' && $ip[1] === '0' && $ip[2] === '113')
        );
    }

    public static function validRoutableIPv6Address(string $value): bool
    {
        $priv_res = filter_var(
            $value,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) !== false;
        if (!$priv_res) {
            return false;
        }
        // implementing the same restrictions as PHP 8.2's FILTER_FLAG_GLOBAL_RANGE
        $ip = str_split(bin2hex(inet_pton($value) ?: ""), 4);

        return !(
            ($ip[0] === '0100' && $ip[1] === '0000' && $ip[2] === '0000' && $ip[3] === '0000')
            || ($ip[0] === '2001' && intval($ip[1], 16) < 0x01ff)
            || ($ip[0] === '2001' && $ip[1] === '0002' && $ip[2] === '0000')
            || (intval($ip[0], 16) >= 0xfc00 && intval($ip[0], 16) <= 0xfdff)
        );
    }

    public static function validRoutableIPAddress(string $value): bool
    {
        return self::validRoutableIPv4Address($value)
            || self::validRoutableIPv6Address($value);
    }

    public static function validRoutableIPv4Network(
        string $value,
        int $low = self::IPV4_RANGE_MIN,
        int $high = self::IPV4_RANGE_MAX
    ): bool
    {
        if (!self::validIPv4Network($value, $low, $high)) {
            return false;
        }

        try {
            [$first, $last] = self::getNetworkRange($value);
        } catch (Throwable) {
            return false;
        }

        return self::validRoutableIPv4Address($first)
            && self::validRoutableIPv4Address($last);
    }

    public static function validRoutableIPv6Network(
        string $value,
        int $low = self::IPV6_RANGE_MIN,
        int $high = self::IPV6_RANGE_MAX
    ): bool
    {
        if (!self::validIPv6Network($value, $low, $high)) {
            return false;
        }

        try {
            [$first, $last] = self::getNetworkRange($value);
        } catch (Throwable) {
            return false;
        }

        return self::validRoutableIPv6Address($first)
            && self::validRoutableIPv6Address($last);
    }

    public static function validRoutableIPNetwork(
        string $value,
        int $low = self::IPV6_RANGE_MIN,
        int $high = self::IPV6_RANGE_MAX
    ): bool
    {
        return self::validRoutableIPv4Network($value, $low, $high)
            || self::validRoutableIPv6Network($value, $low, $high);
    }

    public static function validIPv4Network(
        string $value,
        int $low = self::IPV4_RANGE_MIN,
        int $high = self::IPV4_RANGE_MAX
    ): bool
    {
        if (!str_contains($value, '/')) {
            return false;
        }
        [$network, $mask] = explode('/', $value);
        if ($mask < self::IPV4_RANGE_MIN || $mask > self::IPV4_RANGE_MAX) {
            return false;
        }

        return self::validIPv4Address($network) && self::validRange((int)$mask, $low, $high);
    }

    public static function validIPv6Network(
        string $value,
        int $low = self::IPV6_RANGE_MIN,
        int $high = self::IPV6_RANGE_MAX
    ): bool
    {
        if (!str_contains($value, '/')) {
            return false;
        }
        [$network, $mask] = explode('/', $value);
        if ($mask < self::IPV6_RANGE_MIN || $mask > self::IPV6_RANGE_MAX) {
            return false;
        }

        return self::validIPv6Address($network) && self::validRange((int)$mask, $low, $high);
    }

    public static function validIPNetwork(
        string $value,
        int $low = self::IPV6_RANGE_MIN,
        int $high = self::IPV6_RANGE_MAX
    ): bool
    {
        if (!str_contains($value, '/')) {
            return false;
        }

        [$network, $mask] = explode('/', $value);

        if (!self::validIPv6Address($network)) {
            $high = min($high, self::IPV4_RANGE_MAX);
        }
        if ($high > self::IPV6_RANGE_MAX  || $low > $high || $low < 0) {
            throw new InvalidArgumentException('Invalid network validation rule arguments');
        }

        return self::validIPAddress($network)
            && self::validRange((int)$mask, $low, $high);
    }

    public static function addressWithinNetwork(string $address, string $subnet): bool
    {
        if (self::validIPv4Address($address) && self::validIPv4Network($subnet)) {
            $length = 8;
        } elseif (self::validIPv6Address($address) && self::validIPv6Network($subnet)) {
            $length = 32;
        } else {
            return false;
        }

        [$network, $bits] = explode('/', $subnet);
        $bits = (int)$bits;
        $address = inet_pton($address) ?: "";
        $network = inet_pton($network) ?: "";
        if (
            $bits < self::IPV6_RANGE_MIN
            || $bits > self::IPV6_RANGE_MAX
            || $address === ""
            || $network === ""
        ) {
            return false;
        }
        $mask = self::bitsToPacked($bits, $length);

        return ($address & $mask) === ($network & $mask);
    }

    public static function ipv4NetworksOverlap(string $network1, string $network2): bool
    {
        if (!self::validIPv4Network($network1) || !self::validIPv4Network($network2)) {
            return false;
        }

        [, $bits1] = explode('/', $network1);
        [, $bits2] = explode('/', $network2);

        try {
            [$first, $last] = self::getNetworkRange($bits1 < $bits2 ? $network2 : $network1);
        } catch (Throwable) {
            return false;
        }

        return self::addressWithinNetwork($first, $bits1 < $bits2 ? $network1 : $network2)
            || self::addressWithinNetwork($last, $bits1 < $bits2 ? $network1 : $network2);
    }

    /**
     * @return string[]
     */
    public static function getNetworkRange(string $network): array
    {
        if (self::validIPv6Network($network)) {
            $length = 32;
        } elseif (self::validIPv4Network($network)) {
            $length = 8;
        } else {
            throw new InvalidArgumentException();
        }

        [$address, $bits] = explode('/', $network);
        $bits = (int)$bits;

        $address = inet_pton($address) ?: "";
        if (
            $bits < self::IPV6_RANGE_MIN
            || $bits > self::IPV6_RANGE_MAX
            || $address === ""
        ) {
            throw new InvalidArgumentException();
        }

        $mask = self::bitsToPacked($bits, $length);
        $first = inet_ntop($address & $mask) ?: "";
        $last = inet_ntop($address | ~$mask) ?: "";

        if ($first === "" || $last === "") {
            throw new InvalidArgumentException();
        }

        return [$first, $last];
    }

    /**
     * @param int<self::IPV6_RANGE_MIN,self::IPV6_RANGE_MAX> $bits
     */
    public static function bitsToPacked(int $bits, int $length = 32): string
    {
        $mask = str_repeat('f', intdiv($bits, 4)) . match($bits % 4) {
            0 => '',
            1 => '8',
            2 => 'c',
            3 => 'e',
        };

        return pack('H*', str_pad($mask, $length, '0'));
    }
}
