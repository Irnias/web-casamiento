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

    public function index(Event $event): Factory|View|RedirectResponse
    {
        if (session('guest_event_id') !== $event->id) {
            return redirect()->route('login', $event);
        }

        $code = session('guest_code');
        $guests = $event->guests()->where('invitation_code', $code)->get();

        return view('rsvp', compact('guests', 'code', 'event'));
    }

    public function logout(Event $event): RedirectResponse
    {
        session()->forget(['guest_code', 'guest_event_id']);
        return redirect()->route('login', $event);
    }
}
