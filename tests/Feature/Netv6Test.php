<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(\Miken32\Validation\Network\Rules\Netv6::class)]
class Netv6Test extends TestCase
{
    #[Test]
    public function stringAcceptsBounded(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/64'],
            ['input_test' => 'netv6:60,64']
        );
    }

    #[Test]
    public function stringAcceptsUnbounded(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/64'],
            ['input_test' => 'netv6']
        );
    }

    #[Test]
    public function instanceAcceptsBounded(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/64'],
            ['input_test' => new Rules\Netv6(60, 64)]
        );
    }

    #[Test]
    public function instanceAcceptsUnbounded(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/64'],
            ['input_test' => new Rules\Netv6()]
        );
    }

    #[Test]
    public function stringRejectsBounded(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IPv6 network in CIDR notation with a mask between 60 and 64 bits');
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/48'],
            ['input_test' => 'netv6:60,64']
        );
    }

    #[Test]
    public function stringRejectsUnbounded(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IPv6 network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/200'],
            ['input_test' => 'netv6']
        );
    }

    #[Test]
    public function instanceRejectsBounded(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IPv6 network in CIDR notation with a mask between 60 and 64 bits');
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/48'],
            ['input_test' => new Rules\Netv6(60, 64)]
        );
    }

    #[Test]
    public function instanceRejectsUnbounded(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IPv6 network in CIDR notation with a mask between 0 and 128 bits');
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/200'],
            ['input_test' => new Rules\Netv6()]
        );
    }
}
