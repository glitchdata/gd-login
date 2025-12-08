<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Apple\Provider as AppleProvider;

class SocialiteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Socialite::extend('apple', function ($app) {
            $config = $app['config']['services.apple'] ?? [];

            return Socialite::buildProvider(AppleProvider::class, $config);
        });
    }
}
