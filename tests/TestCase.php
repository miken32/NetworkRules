<?php

namespace Miken32\Validation\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Miken32\Validation\Network\Providers\ValidationProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    use WithFaker;

    protected function getPackageProviders($app): array
    {
        return [ValidationProvider::class];
    }
}
