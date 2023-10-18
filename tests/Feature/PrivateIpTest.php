<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;

/**
 * @covers \Miken32\Validation\Network\Rules\PrivateIp
 */
class PrivateIpTest extends TestCase
{
    /**
     * @test
     */
    public function stringAccepts(): void
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

    /**
     * @test
     */
    public function instanceAccepts(): void
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

    /**
     * @test
     */
    public function stringRejectsIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a private IP address');
        Validator::validate(
            ['input_test' => '23.81.66.30'],
            ['input_test' => 'private_ip']
        );
    }

    /**
     * @test
     */
    public function stringRejectsIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a private IP address');
        Validator::validate(
            ['input_test' => '2600:2342:8ca3:8282::c3ae'],
            ['input_test' => 'private_ip']
        );
    }

    /**
     * @test
     */
    public function instanceRejectsIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a private IP address');
        Validator::validate(
            ['input_test' => '23.81.66.31'],
            ['input_test' => new Rules\PrivateIp()]
        );
    }

    /**
     * @test
     */
    public function instanceRejectsIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a private IP address');
        Validator::validate(
            ['input_test' => '2600:2342:8ca3:8282::c3ae'],
            ['input_test' => new Rules\PrivateIp()]
        );
    }
}
