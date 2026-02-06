<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(\Miken32\Validation\Network\Rules\RoutableIpOrNet::class)]
class RoutableIpOrNetTest extends TestCase
{
    #[Test]
    public function stringAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '1.1.1.1'],
            ['input_test' => 'routable_ip_or_net']
        );
        Validator::validate(
            ['input_test' => $this->publicIpv6()],
            ['input_test' => 'routable_ip_or_net']
        );
        Validator::validate(
            ['input_test' => '1.1.1.1/29'],
            ['input_test' => 'routable_ip_or_net']
        );
        Validator::validate(
            ['input_test' => $this->publicIpv6(true)],
            ['input_test' => 'routable_ip_or_net']
        );
    }

    #[Test]
    public function instanceAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '1.1.1.1'],
            ['input_test' => new Rules\RoutableIpOrNet()]
        );
        Validator::validate(
            ['input_test' => $this->publicIpv6()],
            ['input_test' => new Rules\RoutableIpOrNet()]
        );
        Validator::validate(
            ['input_test' => '1.1.1.1/29'],
            ['input_test' => new Rules\RoutableIpOrNet()]
        );
        Validator::validate(
            ['input_test' => $this->publicIpv6(true)],
            ['input_test' => new Rules\RoutableIpOrNet()]
        );
    }

    #[Test]
    public function stringRejectsIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IP address or IP network in CIDR notation');
        Validator::validate(
            ['input_test' => '10.5.38.218'],
            ['input_test' => 'routable_ip_or_net']
        );

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IP address or IP network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->localIpv4 . '/23'],
            ['input_test' => 'routable_ip_or_net']
        );
    }

    #[Test]
    public function stringRejectsIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IP address or IP network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->publicIpv6()],
            ['input_test' => 'routable_ip_or_net']
        );

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IP address or IP network in CIDR notation');
        $v6 = $this->privateIpv6(true);
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => 'routable_ip_or_net']
        );
    }

    #[Test]
    public function instanceRejectsIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IP address or IP network in CIDR notation');
        Validator::validate(
            ['input_test' => '10.5.38.218'],
            ['input_test' => new Rules\RoutableIpOrNet()]
        );

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IP address or IP network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->localIpv4 . '/23'],
            ['input_test' => new Rules\RoutableIpOrNet()]
        );
    }

    #[Test]
    public function instanceRejectsIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IP address or IP network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->publicIpv6()],
            ['input_test' => new Rules\RoutableIpOrNet()]
        );

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IP address or IP network in CIDR notation');
        $v6 = $this->privateIpv6(true);
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => new Rules\RoutableIpOrNet()]
        );
    }

}
