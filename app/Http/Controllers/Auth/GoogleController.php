<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    /**
     * Send the visitor to Google to choose their account.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Google sends the visitor back here after they sign in.
     * We match them to an existing member by Google ID or email, or
     * create a brand-new account that starts in the "pending" waiting room.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            \Log::error('Google sign-in failed', [
                'message' => $e->getMessage(),
                'query' => request()->query(),
            ]);

            return redirect()->route('login')
                ->with('error', 'Google sign-in was cancelled or failed. Please try again.');
        }

        // Find by Google ID first, then fall back to a matching email
        // (covers members an admin pre-invited by email before they ever logged in).
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        // Bootstrap admins: emails listed in config are auto-approved as admins
        // on first sign-in, so the initial course admins can get in without
        // any server access.
        $isBootstrapAdmin = in_array(
            strtolower((string) $googleUser->getEmail()),
            config('jubilee.admin_emails', []),
            true
        );

        if ($user) {
            $user->forceFill([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                // Only fill the name if we don't already have one.
                'name' => $user->name ?: $googleUser->getName(),
            ]);

            if ($isBootstrapAdmin) {
                $user->forceFill([
                    'role' => 'admin',
                    'status' => 'approved',
                    'approved_at' => $user->approved_at ?? now(),
                ]);
            }

            $user->save();
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getEmail(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'role' => $isBootstrapAdmin ? 'admin' : 'member',
                'status' => $isBootstrapAdmin ? 'approved' : 'pending',
                'approved_at' => $isBootstrapAdmin ? now() : null,
            ]);
        }

        // Blocked accounts never get a session.
        if ($user->isBlocked()) {
            return redirect()->route('login')
                ->with('error', 'Your access to this site has been disabled. Please contact an administrator.');
        }

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard'));
    }
}
