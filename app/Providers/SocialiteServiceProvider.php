<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Apple\Provider as AppleProvider;

class SocialiteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $apple = config('services.apple');

        if ($apple['enabled'] ?? false) {
            Socialite::extend('apple', function ($app) use ($apple) {
                return Socialite::buildProvider(AppleProvider::class, $apple);
            });
        }
    }
}
