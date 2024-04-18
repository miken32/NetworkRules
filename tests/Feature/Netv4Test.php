<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(\Miken32\Validation\Network\Rules\Netv4::class)]

class Netv4Test extends TestCase
{
    #[Test]
    public function stringAcceptsBounded(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/20'],
            ['input_test' => 'netv4:20,24']
        );
    }

    #[Test]
    public function stringAcceptsUnbounded(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/20'],
            ['input_test' => 'netv4']
        );
    }

    #[Test]
    public function instanceAcceptsBounded(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/20'],
            ['input_test' => new Rules\Netv4(20, 24)]
        );
    }

    #[Test]
    public function instanceAcceptsUnbounded(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/20'],
            ['input_test' => new Rules\Netv4()]
        );
    }

    #[Test]
    public function stringRejectsBounded(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IPv4 network in CIDR notation with a mask between 20 and 24 bits');
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/28'],
            ['input_test' => 'netv4:20,24']
        );
    }

    #[Test]
    public function stringRejectsUnbounded(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IPv4 network in CIDR notation');
        Validator::validate(
            ['input_test' => '43.97.4.382/20'],
            ['input_test' => 'netv4']
        );
    }

    #[Test]
    public function instanceRejectsBounded(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IPv4 network in CIDR notation with a mask between 20 and 24 bits');
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/28'],
            ['input_test' => new Rules\Netv4(20, 24)]
        );
    }

    #[Test]
    public function instanceRejectsUnbounded(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IPv4 network in CIDR notation');
        Validator::validate(
            ['input_test' => '43.97.4.382/20'],
            ['input_test' => new Rules\Netv4()]
        );
    }
}
