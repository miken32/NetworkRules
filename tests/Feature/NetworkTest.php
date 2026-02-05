<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Network\Util;
use Miken32\Validation\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(\Miken32\Validation\Network\Rules\Network::class)]

class NetworkTest extends TestCase
{
    #[Test]
    public function stringAcceptsValidIpv4(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/' . $this->faker->numberBetween(Util::IPV4_RANGE_MIN, Util::IPV4_RANGE_MAX)],
            ['input_test' => 'network']
        );
    }

    #[Test]
    public function instanceAcceptsValidIpv4(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/' . $this->faker->numberBetween(Util::IPV4_RANGE_MIN, Util::IPV4_RANGE_MAX)],
            ['input_test' => new Rules\Network()]
        );
    }

    #[Test]
    public function stringAcceptsValidIpv6(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/' . $this->faker->numberBetween(Util::IPV6_RANGE_MIN, Util::IPV6_RANGE_MAX)],
            ['input_test' => 'network']
        );
    }

    #[Test]
    public function instanceAcceptsValidIpv6(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/' . $this->faker->numberBetween(Util::IPV6_RANGE_MIN, Util::IPV6_RANGE_MAX)],
            ['input_test' => new Rules\Network()]
        );
    }

    #[Test]
    public function stringRejectsInvalid(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IPv4 or IPv6 network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->ipv4],
            ['input_test' => 'network']
        );
    }

    #[Test]
    public function instanceRejectsInvalid(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IPv4 or IPv6 network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->ipv6],
            ['input_test' => new Rules\Network()]
        );
    }
}
