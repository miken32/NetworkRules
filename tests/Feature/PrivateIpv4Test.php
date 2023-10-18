<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;

/**
 * @covers \Miken32\Validation\Network\Rules\PrivateIpv4
 */
class PrivateIpv4Test extends TestCase
{
    /**
     * @test
     */
    public function stringAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->localIpv4],
            ['input_test' => 'private_ipv4']
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
            ["input_test" => new Rules\PrivateIpv4()]
        );
    }

    /**
     * @test
     */
    public function stringRejects(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a private IPv4 address');
        Validator::validate(
            ['input_test' => '23.81.66.01'],
            ['input_test' => 'private_ipv4']
        );
    }

    /**
     * @test
     */
    public function instanceRejects(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a private IPv4 address');
        Validator::validate(
            ['input_test' => '23.81.66.01'],
            ['input_test' => new Rules\PrivateIpv4()]
        );
    }
}
