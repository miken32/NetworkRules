<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;

/**
 * @covers \Miken32\Validation\Network\Rules\PrivateIpv6
 */
class PrivateIpv6Test extends TestCase
{
    /**
     * @test
     */
    public function stringAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':'));
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => 'private_ipv6']
        );
    }

    /**
     * @test
     */
    public function instanceAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':'));
        Validator::validate(
            ["input_test" => $v6],
            ["input_test" => new Rules\PrivateIpv6()]
        );
    }

    /**
     * @test
     */
    public function stringRejects(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a private IPv6 address');
        $v6 = $this->faker->ipv6;
        $v6 = '2600' . substr($v6, strpos($v6, ':'));
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => 'private_ipv6']
        );
    }

    /**
     * @test
     */
    public function instanceRejects(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a private IPv6 address');
        $v6 = $this->faker->ipv6;
        $v6 = '2600' . substr($v6, strpos($v6, ':'));
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => new Rules\PrivateIpv6()]
        );
    }
}
