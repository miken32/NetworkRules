<?php

namespace Miken32\Validation\Tests\Unit;

use Miken32\Validation\Network\Util;
use Miken32\Validation\Tests\TestCase;

class UtilTest extends TestCase
{
    /**
     * @covers Util::validRange()
     * @testdox Util::validRange()
     */
    public function testValidRange(): void
    {
        $this->assertTrue(Util::validRange(123, 100, 150));
        $this->assertTrue(Util::validRange(123, 100, 123));
        $this->assertTrue(Util::validRange(123, 123, 150));

        $this->assertFalse(Util::validRange(123, 125, 150));
    }

    /**
     * @covers Util::validIp4Address()
     * @testdox Util::validIp4Address()
     */
    public function testValidIp4Address(): void
    {
        $this->assertTrue(Util::validIPv4Address($this->faker->ipv4));

        $this->assertFalse(Util::validIPv4Address('foo'));
    }

    /**
     * @covers Util::validIPv6Address()
     * @testdox Util::validIPv6Address()
     */
    public function testValidIp6Address(): void
    {
        $this->assertTrue(Util::validIPv6Address($this->faker->ipv6));

        $this->assertFalse(Util::validIPv6Address('foo'));
    }

    /**
     * @covers Util::validIPAddress()
     * @testdox Util::validIPAddress()
     */
    public function testValidIpAddress(): void
    {
        $this->assertTrue(Util::validIPAddress($this->faker->ipv4));
        $this->assertTrue(Util::validIPAddress($this->faker->ipv6));

        $this->assertFalse(Util::validIPAddress('foo'));

    }

    /**
     * @covers Util::validPrivateIPv4Address()
     * @testdox Util::validPrivateIPv4Address()
     */
    public function testValidIp4PrivateAddress(): void
    {
        $this->assertTrue(Util::validPrivateIPv4Address('10.10.0.48'));
        $this->assertTrue(Util::validPrivateIPv4Address('172.16.20.34'));
        $this->assertTrue(Util::validPrivateIPv4Address('192.168.10.128'));

        $this->assertFalse(Util::validPrivateIPv4Address('169.254.229.28'));
        $this->assertFalse(Util::validPrivateIPv4Address('172.12.68.206'));
        $this->assertFalse(Util::validPrivateIPv4Address('240.234.58.22'));
    }

    /**
     * @covers Util::validPrivateIPv6Address()
     * @testdox Util::validPrivateIPv6Address()
     */
    public function testValidIp6PrivateAddress(): void
    {
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':'));
        $this->assertTrue(Util::validPrivateIPv6Address($v6));
        $v6 = $this->faker->ipv6;
        $v6 = '2600' . substr($v6, strpos($v6, ':'));
        $this->assertFalse(Util::validPrivateIPv6Address($v6));
    }

    /**
     * @covers Util::validPrivateIPAddress()
     * @testdox Util::validPrivateIPAddress()
     */
    public function testValidIpPrivateAddress(): void
    {
        $this->assertTrue(Util::validPrivateIPAddress('10.10.0.48'));
        $this->assertTrue(Util::validPrivateIPAddress('172.16.20.34'));
        $this->assertTrue(Util::validPrivateIPAddress('192.168.10.128'));
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':'));
        $this->assertTrue(Util::validPrivateIPAddress($v6));

        $this->assertFalse(Util::validPrivateIPAddress('169.254.229.28'));
        $this->assertFalse(Util::validPrivateIPAddress('172.12.68.206'));
        $this->assertFalse(Util::validPrivateIPAddress('240.234.58.22'));
        $v6 = $this->faker->ipv6;
        $v6 = '2600' . substr($v6, strpos($v6, ':'));
        $this->assertFalse(Util::validPrivateIPAddress($v6));
    }

    /**
     * @covers Util::validRoutableIPv4Address()
     * @testdox Util::validRoutableIPv4Address()
     */
    public function testValidRoutableIP4Address(): void
    {
        $this->assertTrue(Util::validRoutableIPv4Address('1.1.1.1'));
        $this->assertTrue(Util::validRoutableIPv4Address('216.234.62.195'));

        $this->assertFalse(Util::validRoutableIPv4Address('10.10.0.48'));
        $this->assertFalse(Util::validRoutableIPv4Address('172.16.20.34'));
        $this->assertFalse(Util::validRoutableIPv4Address('192.168.10.128'));
        $this->assertFalse(Util::validRoutableIPv4Address('169.254.229.28'));
        $this->assertFalse(Util::validRoutableIPv4Address('172.16.68.206'));
        $this->assertFalse(Util::validRoutableIPv4Address('240.234.58.22'));
    }

    /**
     * @covers Util::validRoutableIPv6Address()
     * @testdox Util::validRoutableIPv6Address()
     */
    public function testValidRoutableIP6Address(): void
    {
        $v6 = $this->faker->ipv6;
        $v6 = '2600' . substr($v6, strpos($v6, ':'));
        $this->assertTrue(Util::validRoutableIPv6Address($v6));
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':'));
        $this->assertFalse(Util::validRoutableIPv6Address($v6));
        $this->assertFalse(Util::validRoutableIPv6Address('2001::f00d:3ac3'));
    }

    /**
     * @covers Util::validRoutableIPAddress()
     * @testdox Util::validRoutableIPAddress()
     */
    public function testValidRoutableAddress(): void
    {
        $this->assertTrue(Util::validRoutableIPAddress('1.1.1.1'));
        $this->assertTrue(Util::validRoutableIPAddress('216.234.62.195'));
        $v6 = $this->faker->ipv6;
        $v6 = '2600' . substr($v6, strpos($v6, ':'));
        $this->assertTrue(Util::validRoutableIPAddress($v6));

        $this->assertFalse(Util::validRoutableIPAddress('10.10.0.48'));
        $this->assertFalse(Util::validRoutableIPAddress('172.16.20.34'));
        $this->assertFalse(Util::validRoutableIPAddress('192.168.10.128'));
        $this->assertFalse(Util::validRoutableIPAddress('169.254.229.28'));
        $this->assertFalse(Util::validRoutableIPAddress('172.16.68.206'));
        $this->assertFalse(Util::validRoutableIPAddress('240.234.58.22'));
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':'));
        $this->assertFalse(Util::validRoutableIPAddress($v6));
        $this->assertFalse(Util::validRoutableIPAddress('2001::f00d:3ac3'));
    }

    /**
     * @covers Util::validIPv4Network()
     * @testdox Util::validIPv4Network()
     */
    public function testValidIp4Subnet(): void
    {
        $this->assertTrue(Util::validIPv4Network($this->faker->ipv4 . '/17'));
        $this->assertTrue(Util::validIPv4Network($this->faker->ipv4 . '/28', 24, 29));

        $this->assertFalse(Util::validIPv4Network($this->faker->ipv6));
        $this->assertFalse(Util::validIPv4Network($this->faker->ipv4));
        $this->assertFalse(Util::validIPv4Network($this->faker->ipv4 . '/28', 20, 24));
    }

    /**
     * @covers Util::validIPv6Network()
     * @testdox Util::validIPv6Network()
     */
    public function testValidIp6Subnet(): void
    {
        $this->assertTrue(Util::validIPv6Network($this->faker->ipv6 . '/56', 48, 56));
        $this->assertTrue(Util::validIPv6Network($this->faker->ipv6 . '/56'));

        $this->assertFalse(Util::validIPv6Network($this->faker->ipv6 . '/64', 48, 56));
        $this->assertFalse(Util::validIPv6Network($this->faker->ipv4));
    }

    /**
     * @covers Util::validIPNetwork()
     * @testdox Util::validIPNetwork()
     */
    public function testValidIpSubnet(): void
    {
        $this->assertTrue(Util::validIPNetwork($this->faker->ipv4 . '/12'));
        $this->assertTrue(Util::validIPNetwork($this->faker->ipv4 . '/28', 24, 29));
        $this->assertTrue(Util::validIPNetwork($this->faker->ipv6 . '/56', 48, 56));
        $this->assertTrue(Util::validIPNetwork($this->faker->ipv6 . '/56'));

        $this->assertFalse(Util::validIPNetwork($this->faker->ipv4 . '/28', 20, 24));
        $this->assertFalse(Util::validIPNetwork($this->faker->ipv6 . '/64', 48, 56));
        $this->assertFalse(Util::validIPNetwork($this->faker->ipv6));
    }

    /**
     * @covers Util::testAddressWithinSubnet()
     * @testdox Util::testAddressWithinSubnet()
     */
    public function testAddressWithinSubnet(): void
    {
        $this->assertTrue(Util::addressWithinNetwork('143.28.58.221', '143.28.58.128/25'));
        $this->assertTrue(Util::addressWithinNetwork('10.10.10.10', '10.10.10.10/32'));

        $this->assertFalse(Util::addressWithinNetwork('142.48.28.118', '192.168.0.0/24'));
    }
}
