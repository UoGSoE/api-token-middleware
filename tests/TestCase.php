<?php

namespace Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(realpath(__DIR__.'/../migrations'));
        $this->artisan('migrate', ['--database' => 'testing']);
    }

    protected function getPackageProviders($app)
    {
        return [
            'UoGSoE\ApiTokenMiddleware\ApiTokenServiceProvider',
        ];
    }

    // protected function resolveApplicationConsoleKernel($app)
    // {
    //     $app->singleton('Illuminate\Contracts\Console\Kernel', 'Acme\Testbench\Console\Kernel');
    // }
}
