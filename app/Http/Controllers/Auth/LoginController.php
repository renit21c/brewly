<?php

namespace App\Http\Controllers\Auth;

use Laravel\Breeze\Http\Controllers\AuthenticatedSessionController as BreezeAuthenticatedSessionController;
use Illuminate\Http\Request;

class LoginController extends BreezeAuthenticatedSessionController
{
    /**
     * The user has been authenticated.
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->isAdmin()) {
            return redirect('/admin/dashboard');
        }

        if ($user->isCashier()) {
            return redirect('/pos');
        }

        return redirect('/dashboard');
    }
}
