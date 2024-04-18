<?php

namespace Miken32\Validation\Tests\Unit;

use Miken32\Validation\Network\Util;
use Miken32\Validation\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;

#[CoversClass(\Miken32\Validation\Network\Util::class)]
class UtilTest extends TestCase
{
    /**
     * @covers Util::validRange()
     * @testdox Util::validRange()
     */
    #[TestDox('Util::validRange()')]
    public function testValidRange(): void
    {
        $this->assertTrue(Util::validRange(123, 100, 150));
        $this->assertTrue(Util::validRange(123, 100, 123));
        $this->assertTrue(Util::validRange(123, 123, 150));

        $this->assertFalse(Util::validRange(123, 125, 150));
    }

    /**
     * @covers Util::validIPv4Address()
     * @testdox Util::validIPv4Address()
     */
    #[TestDox('Util::validIPv4Address()')]
    public function testValidIPv4Address(): void
    {
        $this->assertTrue(Util::validIPv4Address($this->faker->ipv4));

        $this->assertFalse(Util::validIPv4Address('foo'));
    }

    /**
     * @covers Util::validIPv6Address()
     * @testdox Util::validIPv6Address()
     */
    #[TestDox('Util::validIPv6Address()')]
    public function testValidIPv6Address(): void
    {
        $this->assertTrue(Util::validIPv6Address($this->faker->ipv6));

        $this->assertFalse(Util::validIPv6Address('foo'));
    }

    /**
     * @covers Util::validIPAddress()
     * @testdox Util::validIPAddress()
     */
    #[TestDox('Util::validIPAddress()')]
    public function testValidIPAddress(): void
    {
        $this->assertTrue(Util::validIPAddress($this->faker->ipv4));
        $this->assertTrue(Util::validIPAddress($this->faker->ipv6));

        $this->assertFalse(Util::validIPAddress('foo'));

    }

    /**
     * @covers Util::validPrivateIPv4Address()
     * @testdox Util::validPrivateIPv4Address()
     */
    #[TestDox('Util::validPrivateIPv4Address()')]
    public function testValidPrivateIPv4Address(): void
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
    #[TestDox('Util::validPrivateIPv6Address()')]
    public function testValidPrivateIPv6Address(): void
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
    #[TestDox('Util::validPrivateIPAddress()')]
    public function testValidPrivateIPAddress(): void
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
     * @covers Util::validPrivateIPNetwork()
     * @testdox Util::validPrivateIPNetwork()
     */
    #[TestDox('Util::validPrivateIPNetwork()')]
    public function testValidPrivateIPNetwork(): void
    {
        $this->assertTrue(Util::validPrivateIPNetwork('10.0.0.0/8'));
        $this->assertTrue(Util::validPrivateIPNetwork('172.16.12.5/24'));
        $this->assertTrue(Util::validPrivateIPNetwork('192.168.255.255/32'));
        $this->assertTrue(Util::validPrivateIPNetwork('fd00:48ca::/96'));

        $this->assertFalse(Util::validPrivateIPNetwork('8.8.8.0/24'));
        $this->assertFalse(Util::validPrivateIPNetwork('2000:45c3:8c2a:33ac::/64'));
    }

    /**
     * @covers Util::validRoutableIPv4Address()
     * @testdox Util::validRoutableIPv4Address()
     */
    #[TestDox('Util::validRoutableIPv4Address()')]
    public function testValidRoutableIPv4Address(): void
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
    #[TestDox('Util::validRoutableIPv6Address()')]
    public function testValidRoutableIPv6Address(): void
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
    #[TestDox('Util::validRoutableIPAddress()')]
    public function testValidRoutableIPAddress(): void
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
     * @covers Util::validRoutableIPv4Network()
     * @testdox Util::validRoutableIPv4Network()
     */
    #[TestDox('Util::validRoutableIPv4Network()')]
    public function testValidRoutableIPv4Network(): void
    {
        $this->assertTrue(Util::validRoutableIPv4Network('1.1.0.0/16'));
        $this->assertTrue(Util::validRoutableIPv4Network('192.167.0.0/15'));

        $this->assertFalse(Util::validRoutableIPv4Network('127.1.0.0/16'));
        $this->assertFalse(Util::validRoutableIPv4Network('192.168.0.0/15'));
        $this->assertFalse(Util::validRoutableIPv4Network('1.1.0.0/16', 20, 24));
    }

    /**
     * @covers Util::validRoutableIPv6Network()
     * @testdox Util::validRoutableIPv6Network()
     */
    #[TestDox('Util::validRoutableIPv6Network()')]
    public function testValidRoutableIPv6Network(): void
    {
        $this->assertTrue(Util::validRoutableIPv6Network('2000:1234::/32'));

        $this->assertFalse(Util::validRoutableIPv6Network('fd00:1234::/32'));
        $this->assertFalse(Util::validRoutableIPv6Network('2000:1234::/32', 56, 64));
    }

    /**
     * @covers Util::validRoutableIPNetwork()
     * @testdox Util::validRoutableIPNetwork()
     */
    #[TestDox('Util::validRoutableIPNetwork()')]
    public function testValidRoutableIPNetwork(): void
    {
        $this->assertTrue(Util::validRoutableIPv4Network('1.1.0.0/16'));
        $this->assertTrue(Util::validRoutableIPv4Network('192.167.0.0/15'));
        $this->assertTrue(Util::validRoutableIPv6Network('2000:1234::/32'));

        $this->assertFalse(Util::validRoutableIPv4Network('127.1.0.0/16'));
        $this->assertFalse(Util::validRoutableIPv4Network('192.168.0.0/15'));
        $this->assertFalse(Util::validRoutableIPv6Network('fd00:1234::/32'));
    }

    /**
     * @covers Util::validIPv4Network()
     * @testdox Util::validIPv4Network()
     */
    #[TestDox('Util::validIPv4Network()')]
    public function testValidIPv4Network(): void
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
    #[TestDox('Util::validIPv6Network()')]
    public function testValidIPv6Network(): void
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
    #[TestDox('Util::validIPNetwork()')]
    public function testValidIPNetwork(): void
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
    #[TestDox('Util::testAddressWithinSubnet()')]
    public function testAddressWithinSubnet(): void
    {
        $this->assertTrue(Util::addressWithinNetwork('143.28.58.221', '143.28.58.128/25'));
        $this->assertTrue(Util::addressWithinNetwork('10.10.10.10', '10.10.10.10/32'));
        $this->assertTrue(Util::addressWithinNetwork('2600:1a44:ce3a:448c:233c::238', '2600:1a44:ce3a:448c::/64'));

        $this->assertFalse(Util::addressWithinNetwork('142.48.28.118', '192.168.0.0/24'));
        $this->assertFalse(Util::addressWithinNetwork('2600:1a44:ce3a:448c:233c::238', '2600:1a44:ce3a:ffff::/64'));
    }
}
