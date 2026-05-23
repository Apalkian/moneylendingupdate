<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        if (! URL::hasValidSignature($request)) {
            abort(403, 'Invalid or expired verification link.');
        }

        $user = User::query()->findOrFail((int) $request->route('id'));
        $expectedHash = sha1($user->getEmailForVerification());

        if (! hash_equals($expectedHash, (string) $request->route('hash'))) {
            abort(403, 'Invalid verification hash.');
        }

        Auth::login($user, true);

        $targetRoute = $user->isAdmin() ? 'dashboard' : 'borrower.portal';

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route($targetRoute, absolute: false).'?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended(route($targetRoute, absolute: false).'?verified=1');
    }
}
