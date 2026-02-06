<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(\Miken32\Validation\Network\Rules\PrivateIpv6::class)]
class PrivateIpv6Test extends TestCase
{
    #[Test]
    public function stringAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        $v6 = $this->privateIpv6();
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => 'private_ipv6']
        );
    }

    #[Test]
    public function instanceAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        $v6 = $this->privateIpv6();
        Validator::validate(
            ["input_test" => $v6],
            ["input_test" => new Rules\PrivateIpv6()]
        );
    }

    #[Test]
    public function stringRejects(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a private IPv6 address');
        $v6 = $this->publicIpv6();
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => 'private_ipv6']
        );
    }

    #[Test]
    public function instanceRejects(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a private IPv6 address');
        $v6 = $this->publicIpv6();
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => new Rules\PrivateIpv6()]
        );
    }
}
