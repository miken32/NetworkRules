<?php

namespace Miken32\Validation\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Miken32\Validation\Network\Rules;
use Miken32\Validation\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(\Miken32\Validation\Network\Rules\InNetwork::class)]
class InNetworkTest extends TestCase
{
    #[Test]
    public function stringAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '192.168.34.12'],
            ['input_test' => 'in_network:192.168.34.0/24']
        );
        Validator::validate(
            ['input_test' => '10.10.42.58'],
            ['input_test' => 'in_network:192.168.34.0/24,10.0.0.0/8']
        );
        Validator::validate(
            ['input_test' => '2601:44ec:a425::582c'],
            ['input_test' => 'in_network:2601:44ec:a425::/64']
        );
        Validator::validate(
            ['input_test' => '2000:58c3:18ca:44aa::58ce'],
            ['input_test' => 'in_network:2601:44ec:a425::/64,2000:58c3:18ca:44aa::/56']
        );
    }

    #[Test]
    public function instanceAccepts(): void
    {
        $this->expectNotToPerformAssertions();
        Validator::validate(
            ['input_test' => '192.168.34.12'],
            ['input_test' => new Rules\InNetwork('192.168.34.0/24')]
        );
        Validator::validate(
            ['input_test' => '10.10.42.58'],
            ['input_test' => new Rules\InNetwork(['192.168.34.0/24', '10.0.0.0/8'])]
        );
        Validator::validate(
            ['input_test' => '2601:44ec:a425::582c'],
            ['input_test' => new Rules\InNetwork('2601:44ec:a425::/64')]
        );
        Validator::validate(
            ['input_test' => '2000:58c3:18ca:44aa::58ce'],
            ['input_test' => new Rules\InNetwork(['2601:44ec:a425::/64', '2000:58c3:18ca:44aa::/56'])]
        );
    }

    #[Test]
    public function stringRejectsIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IP address within the 10.0.0.0/8 network');
        Validator::validate(
            ['input_test' => '172.16.0.56'],
            ['input_test' => 'in_network:10.0.0.0/8']
        );
    }

    #[Test]
    public function stringRejectsIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IP address within the 2601:44ec:a425e::/64 network');
        Validator::validate(
            ['input_test' => '2608:445d:2183:ce42::582c'],
            ['input_test' => 'in_network:2601:44ec:a425e::/64']
        );
    }

    #[Test]
    public function instanceRejectsIpv4(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IP address within the 10.0.0.0/8 network');
        Validator::validate(
            ['input_test' => '172.16.0.56'],
            ['input_test' => new Rules\InNetwork('10.0.0.0/8')]
        );
    }

    #[Test]
    public function instanceRejectsIpv6(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The input test field must be an IP address within the 2601:44ec:a425::/64 network');
        Validator::validate(
            ['input_test' => '2608:445d:2183:ce42::582c'],
            ['input_test' => new Rules\InNetwork('2601:44ec:a425::/64')]
        );
    }
}
