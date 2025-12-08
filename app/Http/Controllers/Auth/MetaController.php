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

class MetaController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function callback(): RedirectResponse
    {
        $metaUser = Socialite::driver('facebook')->stateless()->user();

        $email = $metaUser->getEmail();

        if (! $email) {
            return redirect()->route('login')->withErrors([
                'email' => 'Your Meta account does not share an email. Please use another sign-in method.',
            ]);
        }

        $name = $metaUser->getName() ?: ($metaUser->getNickname() ?: 'Meta User');

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
            'provider' => 'meta',
            'email' => $user->email,
            'two_factor' => false,
            'remember' => true,
        ]);

        return redirect()->intended(route('dashboard'))
            ->with('status', 'Signed in with Meta');
    }
}
