<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateGuestForEvent
{
    public function handle(Request $request, Closure $next): Response
    {
        $event = $request->route('event');
        if (!session()->has('guest_code') || !session()->has('guest_event_id')) {
            return redirect()->route('login', $event);
        }

        if (session('guest_event_id') !== $event->id) {
            return redirect()->route('login', $event)
                ->withErrors(['code' => 'Tu sesión no corresponde a este evento.']);
        }

        return $next($request);
    }
}
