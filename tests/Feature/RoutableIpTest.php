<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(\Miken32\Validation\Network\Rules\RoutableIp::class)]
class RoutableIpTest extends TestCase
{
    #[Test]
    public function stringAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '1.1.1.1'],
            ['input_test' => 'routable_ip']
        );
        Validator::validate(
            ['input_test' => '2600:482e:1948::21'],
            ['input_test' => 'routable_ip']
        );
    }

    #[Test]
    public function instanceAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '1.1.1.1'],
            ['input_test' => new Rules\RoutableIp()]
        );
        Validator::validate(
            ['input_test' => '2600:482e:1948::21'],
            ['input_test' => new Rules\RoutableIp()]
        );
    }

    #[Test]
    public function stringRejectsIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IP address');
        Validator::validate(
            ['input_test' => '10.5.38.218'],
            ['input_test' => 'routable_ip']
        );
    }

    #[Test]
    public function stringRejectsIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IP address');
        Validator::validate(
            ['input_test' => '2001:0000:0000::f298'],
            ['input_test' => 'routable_ip']
        );
    }

    #[Test]
    public function instanceRejectsIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IP address');
        Validator::validate(
            ['input_test' => '10.5.38.218'],
            ['input_test' => new Rules\RoutableIp()]
        );
    }

    #[Test]
    public function instanceRejectsIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a routable IP address');
        Validator::validate(
            ['input_test' => '2001:0000:0000::f298'],
            ['input_test' => new Rules\RoutableIp()]
        );
    }

}
