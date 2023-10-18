<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;

/**
 * @covers \Miken32\Validation\Network\Rules\RoutableIpv6
 */
class RoutableIpv6Test extends TestCase
{
    /**
     * @test
     */
    public function stringAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '2600:482e:1948::21'],
            ['input_test' => 'routable_ipv6']
        );
    }

    /**
     * @test
     */
    public function instanceAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '2600:482e:1948::21'],
            ['input_test' => new Rules\RoutableIpv6()]
        );
    }

    /**
     * @test
     */
    public function stringRejects(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IPv6 address');
        Validator::validate(
            ['input_test' => 'fd00:48de:ac19::8d'],
            ['input_test' => 'routable_ipv6']
        );
    }

    /**
     * @test
     */
    public function instanceRejects(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IPv6 address');
        Validator::validate(
            ['input_test' => 'fd00:48de:ac19::8d'],
            ['input_test' => new Rules\RoutableIpv6()]
        );
    }
}
