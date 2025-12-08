<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EventLog;
use App\Models\User;
use App\Services\EventLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        if (! $googleUser->getEmail()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Your Google account is missing an email address. Please use a different account or email login.',
            ]);
        }

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName() ?: ($googleUser->getNickname() ?: 'Google User'),
                // Hash random password to satisfy non-null constraint; not used for OAuth sign-ins.
                'password' => Hash::make(Str::random(40)),
            ],
        );

        // Ensure name is refreshed if Google has a newer profile name.
        if (! $user->wasRecentlyCreated && $googleUser->getName() && $user->name !== $googleUser->getName()) {
            $user->name = $googleUser->getName();
            $user->save();
        }

        Auth::login($user, true);

        EventLogger::log(EventLog::TYPE_LOGIN, $user->id, [
            'provider' => 'google',
            'email' => $user->email,
            'two_factor' => false,
            'remember' => true,
        ]);

        return redirect()->intended(route('dashboard'))
            ->with('status', 'Signed in with Google');
    }
}
