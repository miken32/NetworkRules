<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(\Miken32\Validation\Network\Rules\PrivateNet::class)]
class PrivateNetTest extends TestCase
{
    #[Test]
    public function stringAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->localIpv4 . '/20'],
            ['input_test' => 'private_net']
        );
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':'));
        Validator::validate(
            ['input_test' => $v6 . '/64'],
            ['input_test' => 'private_net']
        );
    }

    #[Test]
    public function instanceAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => $this->faker->localIpv4 . '/20'],
            ['input_test' => 'private_net']
        );
        $v6 = $this->faker->ipv6;
        $v6 = 'fd00' . substr($v6, strpos($v6, ':'));
        Validator::validate(
            ['input_test' => $v6 . '/64'],
            ['input_test' => new Rules\PrivateNet()]
        );
    }

    #[Test]
    public function stringRejectsIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a private IP network');
        Validator::validate(
            ['input_test' => '123.45.6.0/28'],
            ['input_test' => 'private_net']
        );
    }

    #[Test]
    public function stringRejectsIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a private IP network');
        $v6 = $this->faker->ipv6;
        $v6 = '2600' . substr($v6, strpos($v6, ':'));
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => 'private_net']
        );
    }

    #[Test]
    public function instanceRejectsIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a private IP network');
        Validator::validate(
            ['input_test' => '123.45.6.0/28'],
            ['input_test' => new Rules\PrivateNet()]
        );
    }

    #[Test]
    public function instanceRejectsIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be a private IP network');
        $v6 = $this->faker->ipv6;
        $v6 = '2600' . substr($v6, strpos($v6, ':'));
        Validator::validate(
            ['input_test' => $v6],
            ['input_test' => new Rules\PrivateNet()]
        );
    }
}
