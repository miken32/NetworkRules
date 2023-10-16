<?php

namespace Miken32\Validation\Tests\Unit;

use Miken32\Validation\Network\Util;
use Miken32\Validation\Tests\TestCase;

class UtilTest extends TestCase
{
    /**
     * @covers Util::testValidRange()
     */
    public function testValidRange(): void
    {
        $this->assertTrue(Util::validRange(123, 100, 150));
        $this->assertTrue(Util::validRange(123, 100, 123));
        $this->assertTrue(Util::validRange(123, 123, 150));

        $this->assertFalse(Util::validRange(123, 125, 150));
    }

    /**
     * @covers Util::testValidIp4Address()
     */
    public function testValidIp4Address(): void
    {
        $this->assertTrue(Util::validIp4Address($this->faker->ipv4));

        $this->assertFalse(Util::validIp4Address('foo'));
    }

    /**
     * @covers Util::testValidIp6Address()
     */
    public function testValidIp6Address(): void
    {
        $this->assertTrue(Util::validIp6Address($this->faker->ipv6));

        $this->assertFalse(Util::validIp6Address('foo'));
    }

    /**
     * @covers Util::testValidIpAddress()
     */
    public function testValidIpAddress(): void
    {
        $this->assertTrue(Util::validIpAddress($this->faker->ipv4));
        $this->assertTrue(Util::validIpAddress($this->faker->ipv6));

        $this->assertFalse(Util::validIpAddress('foo'));

    }

    /**
     * @covers Util::testValidIp4PrivateAddress()
     */
    public function testValidIp4PrivateAddress(): void
    {
        $this->assertTrue(Util::validIp4PrivateAddress('10.10.0.48'));
        $this->assertTrue(Util::validIp4PrivateAddress('172.16.20.34'));
        $this->assertTrue(Util::validIp4PrivateAddress('192.168.10.128'));

        $this->assertFalse(Util::validIp4PrivateAddress('169.254.229.28'));
        $this->assertFalse(Util::validIp4PrivateAddress('172.12.68.206'));
        $this->assertFalse(Util::validIp4PrivateAddress('240.234.58.22'));
    }

    /**
     * @covers Util::testValidIp6PrivateAddress()
     */
    public function testValidIp6PrivateAddress(): void
    {
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':'));
        $this->assertTrue(Util::validIp6PrivateAddress($v6));
        $v6 = $this->faker->ipv6;
        $v6 = '2600' . substr($v6, strpos($v6, ':'));
        $this->assertFalse(Util::validIp6PrivateAddress($v6));
    }

    /**
     * @covers Util::testValidIpPrivateAddress()
     */
    public function testValidIpPrivateAddress(): void
    {
        $this->assertTrue(Util::validIpPrivateAddress('10.10.0.48'));
        $this->assertTrue(Util::validIpPrivateAddress('172.16.20.34'));
        $this->assertTrue(Util::validIpPrivateAddress('192.168.10.128'));
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':'));
        $this->assertTrue(Util::validIpPrivateAddress($v6));

        $this->assertFalse(Util::validIpPrivateAddress('169.254.229.28'));
        $this->assertFalse(Util::validIpPrivateAddress('172.12.68.206'));
        $this->assertFalse(Util::validIpPrivateAddress('240.234.58.22'));
        $v6 = $this->faker->ipv6;
        $v6 = '2600' . substr($v6, strpos($v6, ':'));
        $this->assertFalse(Util::validIpPrivateAddress($v6));
    }

    /**
     * @covers Util::testValidIp4Subnet()
     */
    public function testValidIp4Subnet(): void
    {
        $this->assertTrue(Util::validIp4Subnet($this->faker->ipv4 . '/17'));
        $this->assertTrue(Util::validIp4Subnet($this->faker->ipv4 . '/28', 24, 29));

        $this->assertFalse(Util::validIp4Subnet($this->faker->ipv6));
        $this->assertFalse(Util::validIp4Subnet($this->faker->ipv4));
        $this->assertFalse(Util::validIp4Subnet($this->faker->ipv4 . '/28', 20, 24));
    }

    /**
     * @covers Util::testValidIp6Subnet()
     */
    public function testValidIp6Subnet(): void
    {
        $this->assertTrue(Util::validIp6Subnet($this->faker->ipv6 . '/56', 48, 56));
        $this->assertTrue(Util::validIp6Subnet($this->faker->ipv6 . '/56'));

        $this->assertFalse(Util::validIp6Subnet($this->faker->ipv6 . '/64', 48, 56));
        $this->assertFalse(Util::validIp6Subnet($this->faker->ipv4));
    }

    /**
     * @covers Util::testValidIpSubnet()
     */
    public function testValidIpSubnet(): void
    {
        $this->assertTrue(Util::validIpSubnet($this->faker->ipv4 . '/12'));
        $this->assertTrue(Util::validIpSubnet($this->faker->ipv4 . '/28', 24, 29));
        $this->assertTrue(Util::validIpSubnet($this->faker->ipv6 . '/56', 48, 56));
        $this->assertTrue(Util::validIpSubnet($this->faker->ipv6 . '/56'));

        $this->assertFalse(Util::validIpSubnet($this->faker->ipv4 . '/28', 20, 24));
        $this->assertFalse(Util::validIpSubnet($this->faker->ipv6 . '/64', 48, 56));
        $this->assertFalse(Util::validIpSubnet($this->faker->ipv6));
    }

    /**
     * @covers Util::testAddressWithinSubnet()
     */
    public function testAddressWithinSubnet(): void
    {
        $this->assertTrue(Util::addressWithinSubnet('143.28.58.221', '143.28.58.128/25'));
        $this->assertTrue(Util::addressWithinSubnet('10.10.10.10', '10.10.10.10/32'));

        $this->assertFalse(Util::addressWithinSubnet('142.48.28.118', '192.168.0.0/24'));
    }
}
