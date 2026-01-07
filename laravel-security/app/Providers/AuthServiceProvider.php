<?php

namespace App\Providers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
        Auth::viaRequest('token', function ($request) {
            return User::where('token', $request->header('API-Key'))->first();
        });

        Gate::define('get-contact', function (User $user, Contact $contact) {
            return $user->id === $contact->user_id;
        });
        Gate::define('update-contact', function (User $user, Contact $contact) {
            return $user->id === $contact->user_id;
        });
        Gate::define('delete-contact', function (User $user, Contact $contact) {
            return $user->id === $contact->user_id;
        });

        // Gate with Response
        Gate::define('create-contact', function (User $user) {
            if ($user->name == 'admin') {
                return Response::allow();
            } else {
                return Response::deny('You are not admin');
            }
        });
    }

    
}
