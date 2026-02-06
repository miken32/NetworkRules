<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(\Miken32\Validation\Network\Rules\PrivateNetv6::class)]
class PrivateNetv6Test extends TestCase
{
    #[Test]
    public function stringAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        $v6 = $this->privateIpv6(true);
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => 'private_netv6']
        );
    }

    #[Test]
    public function instanceAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        $v6 = $this->privateIpv6(true);
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => new Rules\PrivateNetv6()]
        );
    }

    #[Test]
    public function stringRejects(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a private IPv6 network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->publicIpv6(true)],
            ['input_test' => 'private_netv6']
        );
    }

    #[Test]
    public function instanceRejects(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a private IPv6 network in CIDR notation');
        Validator::validate(
            ['input_test' => $this->publicIpv6(true)],
            ['input_test' => new Rules\PrivateNetv6()]
        );
    }
}
