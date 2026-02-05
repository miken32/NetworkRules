<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(\Miken32\Validation\Network\Rules\PrivateNetv4::class)]
class PrivateNetv4Test extends TestCase
{
    #[Test]
    public function stringAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->localIpv4 . '/29'],
            ['input_test' => 'private_netv4']
        );
    }

    #[Test]
    public function instanceAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->localIpv4 . '/29'],
            ['input_test' => new Rules\PrivateNetv4()]
        );
    }

    #[Test]
    public function stringRejects(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a private IPv4 network in CIDR notation');
        Validator::validate(
            ['input_test' => '1.1.1.0/23'],
            ['input_test' => 'private_netv4']
        );
    }

    #[Test]
    public function instanceRejects(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a private IPv4 network in CIDR notation');
        Validator::validate(
            ['input_test' => '1.1.1.0/23'],
            ['input_test' => new Rules\PrivateNetv4()]
        );
    }
}
