<?php

namespace Miken32\Validation\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Miken32\Validation\Network\Providers\ArrProvider;
use Miken32\Validation\Network\Providers\ValidationProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    use WithFaker;

    protected function getPackageProviders($app): array
    {
        return [ArrProvider::class, ValidationProvider::class];
    }

    protected function privateIpv6(bool $mask = false): string
    {
        $v6 = $this->faker->ipv6;
        if ($mask) {
            $v6 .= '/' . $this->faker->numberBetween(8, 128);
        }

        return 'fd00' . substr($v6, strpos($v6, ':') ?: 0);
    }

    protected function publicIpv6(bool $mask = false): string
    {
        $v6 = $this->faker->ipv6;
        if ($mask) {
            $v6 .= '/' . $this->faker->numberBetween(4, 128);
        }

        return '2600' . substr($v6, strpos($v6, ':') ?: 0);
    }
}
