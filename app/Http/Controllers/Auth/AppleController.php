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

class AppleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('apple')->stateless()->redirect();
    }

    public function callback(): RedirectResponse
    {
        $appleUser = Socialite::driver('apple')->stateless()->user();

        $email = $appleUser->getEmail();

        if (! $email) {
            $appleId = $appleUser->getId();
            if ($appleId) {
                $email = $appleId.'@apple-privacy.local';
            }
        }

        if (! $email) {
            return redirect()->route('login')->withErrors([
                'email' => 'We could not obtain an email from Apple. Please use another sign-in method.',
            ]);
        }

        $name = $appleUser->getName() ?: ($appleUser->user['name'] ?? null) ?: 'Apple User';

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make(Str::random(40)),
            ],
        );

        if (! $user->wasRecentlyCreated && $name && $user->name !== $name) {
            $user->name = $name;
            $user->save();
        }

        Auth::login($user, true);

        EventLogger::log(EventLog::TYPE_LOGIN, $user->id, [
            'provider' => 'apple',
            'email' => $user->email,
            'two_factor' => false,
            'remember' => true,
        ]);

        return redirect()->intended(route('dashboard'))
            ->with('status', 'Signed in with Apple ID');
    }
}
