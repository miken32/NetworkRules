<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;

/**
 * @covers \Miken32\Validation\Network\Rules\RoutableNet
 */
class RoutableNetTest extends TestCase
{
    /**
     * @test
     */
    public function stringAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '1.1.1.1/29'],
            ['input_test' => 'routable_net']
        );
        Validator::validate(
            ['input_test' => '2600:2345:23ac::/56'],
            ['input_test' => 'routable_net']
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
            ['input_test' => new Rules\RoutableNet()]
        );
        Validator::validate(
            ['input_test' => '2600:2345:23ac::/56'],
            ['input_test' => new Rules\RoutableNet()]
        );
    }

    /**
     * @test
     */
    public function stringRejectsIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IP network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->localIpv4 . '/23'],
            ['input_test' => 'routable_net']
        );
    }

    /**
     * @test
     */
    public function stringRejectsIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IP network in CIDR notation');
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':')) . '/64';
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => 'routable_net']
        );
    }

    /**
     * @test
     */
    public function instanceRejectsIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IP network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->faker->localIpv4 . '/23'],
            ['input_test' => new Rules\RoutableNet()]
        );
    }

    /**
     * @test
     */
    public function instanceRejectsIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IP network in CIDR notation');
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':')) . '/64';
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => new Rules\RoutableNet()]
        );
    }
}
