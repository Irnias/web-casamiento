<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm(): Factory|View
    {
        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();
            $event = $user->events->first();

            if ($event) {
                return redirect()->route('dashboard', $event);
            }

            Auth::logout();
            return back()->withErrors([
                'email' => 'No tienes ningún evento asignado para administrar.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }
}
