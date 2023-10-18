<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;

/**
 * @covers \Miken32\Validation\Network\Rules\RoutableNetv4
 */
class RoutableNetv4Test extends TestCase
{
    /**
     * @test
     */
    public function stringAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '1.1.1.1/29'],
            ['input_test' => 'routable_netv4']
        );
    }

    /**
     * @test
     */
    public function instanceAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '1.1.1.1/29'],
            ['input_test' => new Rules\RoutableNetv4()]
        );
    }

    /**
     * @test
     */
    public function stringRejects(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IPv4 network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->localIpv4 . '/23'],
            ['input_test' => 'routable_netv4']
        );
    }

    /**
     * @test
     */
    public function instanceRejects(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IPv4 network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->localIpv4 . '/23'],
            ['input_test' => new Rules\RoutableNetv4()]
        );
    }
}
