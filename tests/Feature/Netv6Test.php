<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;

/**
 * @covers \Miken32\Validation\Network\Rules\Netv6
 */
class Netv6Test extends TestCase
{
    /**
     * @test
     */
    public function stringAcceptsBounded(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/64'],
            ['input_test' => 'netv6:60,64']
        );
    }

    /**
     * @test
     */
    public function stringAcceptsUnbounded(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/64'],
            ['input_test' => 'netv6']
        );
    }

    /**
     * @test
     */
    public function instanceAcceptsBounded(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/64'],
            ['input_test' => new Rules\Netv6(60, 64)]
        );
    }

    /**
     * @test
     */
    public function instanceAcceptsUnbounded(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/64'],
            ['input_test' => new Rules\Netv6()]
        );
    }

    /**
     * @test
     */
    public function stringRejectsBounded(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IPv6 network in CIDR notation with a mask between 60 and 64 bits');
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/48'],
            ['input_test' => 'netv6:60,64']
        );
    }

    /**
     * @test
     */
    public function stringRejectsUnbounded(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IPv6 network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/200'],
            ['input_test' => 'netv6']
        );
    }

    /**
     * @test
     */
    public function instanceRejectsBounded(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IPv6 network in CIDR notation with a mask between 60 and 64 bits');
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . '/48'],
            ['input_test' => new Rules\Netv6(60, 64)]
        );
    }

    /**
     * @test
     */
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
