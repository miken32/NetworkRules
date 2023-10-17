<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Tests\TestCase;

class StringValidationTests extends TestCase
{
    public function testItAcceptsValidPrivateIpv4(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->localIpv4],
            ['input_test' => 'private_ipv4']
        );
    }

    public function testItAcceptsValidPrivateIpv6(): void
    {
        $this->expectNotToPerformAssertions();
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':'));
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => 'private_ipv6']
        );
    }

    public function testItAcceptsValidPrivateIp(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->localIpv4],
            ['input_test' => 'private_ip']
        );
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':'));
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => 'private_ip']
        );
    }

    public function testItRejectsInvalidPrivateIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid private IPv4 address');
        Validator::validate(
            ['input_test' => '23.81.66.01'],
            ['input_test' => 'private_ipv4']
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
            ['input_test' => 'private_ipv6']
        );
    }

    public function testItRejectsInvalidPrivateIp(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid private IP address');
        Validator::validate(
            ['input_test' => '23.81.66.30'],
            ['input_test' => 'private_ip']
        );
    }

    public function testItAcceptsValidBoundedIpv4Subnet(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/20'],
            ['input_test' => 'netv4:20,24']
        );
    }

    public function testItAcceptsValidBoundedIpv6Subnet(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/64'],
            ['input_test' => 'netv6:60,64']
        );
    }

    public function testItAcceptsValidUnboundedIpv4Subnet(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/20'],
            ['input_test' => 'netv4']
        );
    }

    public function testItAcceptsValidUnboundedIpv6Subnet(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/64'],
            ['input_test' => 'netv6']
        );
    }

    public function testItAcceptsValidUnboundedIpSubnet(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/24'],
            ['input_test' => 'ip_or_net']
        );
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/64'],
            ['input_test' => 'ip_or_net']
        );
    }

    public function testItRejectsInvalidBoundedIpv4Subnet(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid IPv4 subnet in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/28'],
            ['input_test' => 'netv4:20,24']
        );
    }

    public function testItRejectsInvalidBoundedIpv6Subnet(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid IPv6 subnet in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/48'],
            ['input_test' => 'netv6:60,64']
        );
    }

    public function testItRejectsInvalidUnboundedIpv4Subnet(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid IPv4 subnet in CIDR notation');
        Validator::validate(
            ['input_test' => '43.97.4.382/20'],
            ['input_test' => 'netv4']
        );
    }

    public function testItRejectsInvalidUnboundedIpv6Subnet(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid IPv6 subnet in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/200'],
            ['input_test' => 'netv6']
        );
    }

    public function testItRejectsInvalidUnboundedIpSubnetWithIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid IP address, or a subnet in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/48'],
            ['input_test' => 'ip_or_net']
        );
    }

    public function testItRejectsInvalidUnboundedIpSubnetWithIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid IP address, or a subnet in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . 'x/64'],
            ['input_test' => 'ip_or_net']
        );
    }

    public function testItAcceptsValidAddressesInNetwork(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '192.168.34.12'],
            ['input_test' => 'in_network:192.168.34.0/24']
        );
        Validator::validate(
            ['input_test' => '10.10.42.58'],
            ['input_test' => 'in_network:192.168.34.0/24,10.0.0.0/8']
        );
        Validator::validate(
            ['input_test' => '2601:44ec:a425::582c'],
            ['input_test' => 'in_network:2601:44ec:a425::/64']
        );
        Validator::validate(
            ['input_test' => '2000:58c3:18ca:44aa::58ce'],
            ['input_test' => 'in_network:2601:44ec:a425::/64,2000:58c3:18ca:44aa::/56']
        );
    }
    public function testItRejectsInvalidAddressesInNetworkWithIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid IP address within the specified subnet');
        Validator::validate(
            ['input_test' => '172.16.0.56'],
            ['input_test' => 'in_network:10.0.0.0/8']
        );
    }

    public function testItRejectsInvalidAddressesInNetworkWithIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid IP address within the specified subnet');
        Validator::validate(
            ['input_test' => '2608:445d:2183:ce42::582c'],
            ['input_test' => 'in_network:2601:44ec:a425e::/64']
        );
    }

    public function testItAcceptsValidRoutableIpv4Addresses(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '1.1.1.1'],
            ['input_test' => 'routable_ipv4']
        );
    }

    public function testItAcceptsValidRoutableIpv6Addresses(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '2600:482e:1948::21'],
            ['input_test' => 'routable_ipv6']
        );
    }
    public function testItAcceptsValidRoutableIpAddresses(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '1.1.1.1'],
            ['input_test' => 'routable_ip']
        );
        Validator::validate(
            ['input_test' => '2600:482e:1948::21'],
            ['input_test' => 'routable_ip']
        );
    }

    public function testItRejectsInvalidRoutableIpv4Addresses(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid routable IPv4 address');
        Validator::validate(
            ['input_test' => '192.168.24.10'],
            ['input_test' => 'routable_ipv4']
        );
    }

    public function testItRejectsInvalidRoutableIpv6Addresses(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid routable IPv6 address');
        Validator::validate(
            ['input_test' => 'fd00:48de:ac19::8d'],
            ['input_test' => 'routable_ipv6']
        );
    }

    public function testItRejectsInvalidRoutableIpAddressesWithIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid routable IP address');
        Validator::validate(
            ['input_test' => '10.5.38.218'],
            ['input_test' => 'routable_ip']
        );
    }

    public function testItRejectsInvalidRoutableAddressesWithIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid routable IP address');
        Validator::validate(
            ['input_test' => '2001:0000:0000::f298'],
            ['input_test' => 'routable_ip']
        );
    }

    public function testItAcceptsValidPrivateNetworks(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->localIpv4 . '/20'],
            ['input_test' => 'private_net']
        );
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':'));
        Validator::validate(
            ['input_test' => $v6 . '/64'],
            ['input_test' => 'private_net']
        );
    }

    public function testItRejectsInvalidPrivateNetworksWithIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid private IP network');
        Validator::validate(
            ['input_test' => '123.45.6.0/28'],
            ['input_test' => 'private_net']
        );
    }

    public function testItRejectsInvalidPrivateNetworksWithIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid private IP network');
        $v6 = $this->faker->ipv6;
        $v6 = '2600' . substr($v6, strpos($v6, ':'));
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => 'private_net']
        );
    }

    public function testItAcceptsValidRoutableIpv4Networks(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '1.1.1.1/29'],
            ['input_test' => 'routable_netv4']
        );
    }

    public function testItAcceptsValidRoutableIpv6Networks(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '2600:2345:23ac::/56'],
            ['input_test' => 'routable_netv6']
        );
    }

    public function testItAcceptsValidRoutableIpNetworks(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '1.1.1.1/29'],
            ['input_test' => 'routable_net']
        );
        Validator::validate(
            ['input_test' => '2600:2345:23ac::/56'],
            ['input_test' => 'routable_net']
        );
    }

    public function testItRejectsInvalidRoutableIpv4Networks(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid routable IPv4 network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->localIpv4 . '/23'],
            ['input_test' => 'routable_netv4']
        );
    }

    public function testItRejectsInvalidRoutableIpv6Networks(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid routable IPv6 network in CIDR notation');
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':')) . '/64';
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => 'routable_netv6']
        );
    }

    public function testItRejectsInvalidRoutableIpNetworksWithIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid routable IP network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->localIpv4 . '/23'],
            ['input_test' => 'routable_net']
        );
    }

    public function testItRejectsInvalidRoutableIpNetworksWithIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a valid routable IP network in CIDR notation');
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':')) . '/64';
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => 'routable_net']
        );
    }
}
