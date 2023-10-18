<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;

/**
 * @covers \Miken32\Validation\Network\Rules\RoutableIpv4
 */
class RoutableIpv4Test extends TestCase
{
    /**
     * @test
     */
    public function stringAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '1.1.1.1'],
            ['input_test' => 'routable_ipv4']
        );
    }

    /**
     * @test
     */
    public function instanceAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '1.1.1.1'],
            ['input_test' => new Rules\RoutableIpv4()]
        );
    }

    /**
     * @test
     */
    public function stringRejects(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IPv4 address');
        Validator::validate(
            ['input_test' => '192.168.24.10'],
            ['input_test' => 'routable_ipv4']
        );
    }

    /**
     * @test
     */
    public function instanceRejects(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IPv4 address');
        Validator::validate(
            ['input_test' => '192.168.24.10'],
            ['input_test' => new Rules\RoutableIpv4()]
        );
    }
}
