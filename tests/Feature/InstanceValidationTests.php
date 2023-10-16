<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;

class InstanceValidationTests extends TestCase
{
    public function testItAcceptsValidPrivateIpv4(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ["input_test" => $this->faker->localIpv4],
            ["input_test" => new Rules\PrivateIpv4()]
        );
    }

    public function testItAcceptsValidPrivateIpv6(): void
    {
        $this->expectNotToPerformAssertions();
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':'));
        Validator::validate(
            ["input_test" => $v6],
            ["input_test" => new Rules\PrivateIpv6()]
        );
    }

    public function testItAcceptsValidPrivateIp(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ["input_test" => $this->faker->localIpv4],
            ["input_test" => new Rules\PrivateIp()]
        );
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':'));
        Validator::validate(
            ["input_test" => $v6],
            ["input_test" => new Rules\PrivateIp()]
        );
    }

    public function testItRejectsInvalidPrivateIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid private IPv4 address');
        Validator::validate(
            ['input_test' => '23.81.66.01'],
            ['input_test' => new Rules\PrivateIpv4()]
        );
    }

    public function testItRejectsInvalidPrivateIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid private IPv6 address');
        $v6 = $this->faker->ipv6;
        $v6 = '2600' . substr($v6, strpos($v6, ':'));
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => new Rules\PrivateIpv6()]
        );
    }

    public function testItRejectsInvalidPrivateIp(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid private IP address');
        Validator::validate(
            ['input_test' => '23.81.66.31'],
            ['input_test' => new Rules\PrivateIp()]
        );
    }

    public function testItAcceptsValidBoundedIpv4Subnet(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/20'],
            ['input_test' => new Rules\Netv4(20, 24)]
        );
    }

    public function testItAcceptsValidBoundedIpv6Subnet(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/64'],
            ['input_test' => new Rules\Netv6(60, 64)]
        );
    }

    public function testItAcceptsValidBoundedIpSubnet(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/24'],
            ['input_test' => new Rules\IpOrNet()]
        );
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/64'],
            ['input_test' => new Rules\IpOrNet()]
        );
    }

    public function testItAcceptsValidUnboundedIpv4Subnet(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/20'],
            ['input_test' => new Rules\Netv4()]
        );
    }

    public function testItAcceptsValidUnboundedIpv6Subnet(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/64'],
            ['input_test' => new Rules\Netv6()]
        );
    }

    public function testItAcceptsValidUnboundedIpSubnet(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/24'],
            ['input_test' => new Rules\IpOrNet()]
        );
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/64'],
            ['input_test' => new Rules\IpOrNet()]
        );
    }

    public function testItRejectsInvalidBoundedIpv4Subnet(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must have a network mask between 20 and 24 bits');
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/28'],
            ['input_test' => new Rules\Netv4(20, 24)]
        );
    }

    public function testItRejectsInvalidBoundedIpv6Subnet(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must have a network mask between 60 and 64 bits');
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/48'],
            ['input_test' => new Rules\Netv6(60, 64)]
        );
    }

    public function testItRejectsInvalidUnboundedIpv4Subnet(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid IPv4 subnet in CIDR notation');
        Validator::validate(
            ['input_test' => '43.97.4.382/20'],
            ['input_test' => new Rules\Netv4()]
        );
    }

    public function testItRejectsInvalidUnboundedIpv6Subnet(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must have a network mask between 0 and 128 bits');
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/200'],
            ['input_test' => new Rules\Netv6()]
        );
    }

    public function testItRejectsInvalidUnboundedIpSubnetWithIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid IP address, or a subnet in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/48'],
            ['input_test' => new Rules\IpOrNet()]
        );
    }

    public function testItRejectsInvalidUnboundedIpSubnetWithIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid IP address, or a subnet in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . 'x/64'],
            ['input_test' => new Rules\IpOrNet()]
        );
    }

    public function testItRejectsInvalidAddressesInNetworkWithIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid IP address within the 10.0.0.0/8 subnet');
        Validator::validate(
            ['input_test' => '172.16.0.56'],
            ['input_test' => new Rules\InNetwork('10.0.0.0/8')]
        );
    }

    public function testItRejectsInvalidAddressesInNetworkWithIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid IP address within the 2601:44ec:a425e::/64 subnet');
        Validator::validate(
            ['input_test' => '2608:445d:2183:ce42::582c'],
            ['input_test' => new Rules\InNetwork('2601:44ec:a425e::/64')]
        );
    }
}