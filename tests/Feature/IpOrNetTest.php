<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;

/**
 * @covers \Miken32\Validation\Network\Rules\IpOrNet
 */
class IpOrNetTest extends TestCase
{
    /**
     * @test
     */
    public function stringAccepts(): void
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

    /**
     * @test
     */
    public function instanceAccepts(): void
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

    /**
     * @test
     */
    public function stringRejectsIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IP address, or a network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/48'],
            ['input_test' => 'ip_or_net']
        );
    }

    /**
     * @test
     */
    public function stringRejectsIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IP address, or a network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . 'x/64'],
            ['input_test' => 'ip_or_net']
        );
    }

    /**
     * @test
     */
    public function instanceRejectsIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IP address, or a network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->ipv4 . '/48'],
            ['input_test' => new Rules\IpOrNet()]
        );
    }

    /**
     * @test
     */
    public function instanceRejectsIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IP address, or a network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->ipv6 . 'x/64'],
            ['input_test' => new Rules\IpOrNet()]
        );
    }
}
