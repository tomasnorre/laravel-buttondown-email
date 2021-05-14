<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\ButtonDownEmail;

use Illuminate\Support\ServiceProvider;
use JustSteveKing\UriBuilder\Uri;

class ButtonDownServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/buttondown.php' => config_path('buttondown.php'),
            ], 'config');
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/buttondown.php',
            'buttondown',
        );

        $this->app->bind(Client::class, function ($app) {
            return new Client(
                url: Uri::fromString(
                    uri: config('buttondown.api.url'),
                ),
                apiKey: config('buttondown.api.key'),
                timeout: config('buttondown.api.timeout'),
                retryTimes: config('buttondown.api.retry.times'),
                retryMilliseconds: config('buttondown.api.retry.milliseconds'),
            );
        });
    }
}
