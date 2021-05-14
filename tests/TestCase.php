<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\ButtonDownEmail\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use JustSteveKing\Laravel\ButtonDownEmail\ButtonDownServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $env = parse_ini_file(__DIR__ . '/../.env.ci');

        $env = array_merge($env, $_ENV);

        $this->app['config']->set(
            'buttondown.api.key',
            $env['BUTTONDOWN_KEY'],
        );
        $this->app['config']->set(
            'buttondown.api.url',
            $env['BUTTONDOWN_URL'],
        );
        $this->app['config']->set(
            'buttondown.api.timeout',
            $env['BUTTONDOWN_TIMEOUT'],
        );
        $this->app['config']->set(
            'buttondown.api.retry.times',
            $env['BUTTONDOWN_RETRY_TIMES'],
        );
        $this->app['config']->set(
            'buttondown.api.retry.milliseconds',
            $env['BUTTONDOWN_RETRY_MILLISECONDS'],
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ButtonDownServiceProvider::class,
        ];
    }
}
