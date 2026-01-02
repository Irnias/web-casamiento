<?php

namespace App\Http\Controllers;

use App\Data\LoginData;
use App\Models\Event;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class RsvpController extends Controller
{
    public function showLogin(Event $event): Factory|View|RedirectResponse
    {
        if (session('guest_event_id') === $event->id && session()->has('guest_code')) {
            return redirect()->route('rsvp.index', $event);
        }
        return view('login', compact('event'));
    }

    public function login(Event $event): Factory|View|RedirectResponse
    {
        if (session()->has("guest_session_{$event->id}")) {
            return redirect()->route('rsvp.index', $event);
        }

        return view('guest.login', compact('event'));
    }

    public function authenticate(LoginData $data, Event $event): RedirectResponse
    {
        $guest = $event->guests()
            ->where('invitation_code', $data->code)
            ->first();

        if (! $guest) {
            return back()->withErrors(['code' => 'El código no es válido para este evento.']);
        }

        session(["guest_session_{$event->id}" => $guest->id]);

        return redirect()->route('rsvp.index', $event);
    }

    public function processLogin(Event $event, LoginData $data): RedirectResponse
    {
        $guest = $event->guests()->where('invitation_code', $data->code)->first();

        if ($guest) {
            session([
                'guest_code' => $guest->invitation_code,
                'guest_event_id' => $event->id
            ]);

            return redirect()->route('rsvp.index', $event);
        }

        return back()->withErrors(['code' => 'Código no encontrado en este evento. Revisa tu invitación.']);
    }

    public function index(Event $event): Factory|View
    {
        $guestId = session("guest_session_{$event->id}");
        $guest = $event->guests()->findOrFail($guestId);

        return view('rsvp', compact('event', 'guest'));
    }

    public function logout(Event $event): RedirectResponse
    {
        session()->forget("guest_session_{$event->id}");
        return redirect()->route('rsvp.login', $event);
    }
}
