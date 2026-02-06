<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(\Miken32\Validation\Network\Rules\RoutableNetv6::class)]
class RoutableNetv6Test extends TestCase
{
    #[Test]
    public function stringAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->publicIpv6(true)],
            ['input_test' => 'routable_netv6']
        );
    }

    #[Test]
    public function instanceAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->publicIpv6(true)],
            ['input_test' => new Rules\RoutableNetv6()]
        );
    }

    #[Test]
    public function stringRejects(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IPv6 network in CIDR notation');
        $v6 = $this->privateIpv6(true);
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => 'routable_netv6']
        );
    }

    #[Test]
    public function instanceRejects(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IPv6 network in CIDR notation');
        $v6 = $this->privateIpv6(true);
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => new Rules\RoutableNetv6()]
        );
    }
}
