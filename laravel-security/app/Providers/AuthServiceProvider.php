<?php

namespace App\Providers;

use App\Providers\Guard\TokenGuard;
use Illuminate\Auth\RequestGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Auth::extend('token', function (Application $app, string $name, array $config) {
            $provider = Auth::createUserProvider($config['provider']);

            return new RequestGuard(
                fn($request) => $provider->retrieveByCredentials([
                    'api_token' => $request->bearerToken()
                ]),
                $app['request']
            );
        });
    }
}
