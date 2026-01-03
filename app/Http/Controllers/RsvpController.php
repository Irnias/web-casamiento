<?php

namespace App\Http\Controllers;

use App\Data\LoginData;
use App\Models\Event;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RsvpController extends Controller
{
    public function login(Event $event): Factory|View|RedirectResponse
    {
        if ($this->isGuestLoggedIn($event)) {
            return redirect()->route('rsvp.index', $event);
        }

        return view('guest.login', compact('event'));
    }

    public function authenticate(LoginData $data, Event $event): RedirectResponse
    {
        $guest = $event->guests()->where('invitation_code', $data->code)->first();

        if (! $guest) {
            return back()->withErrors(['code' => 'Código no válido para este evento.']);
        }

        $this->logInGuest($event, $guest->invitation_code);

        return redirect()->route('rsvp.index', $event);
    }

    public function magicLogin(Event $event, string $code): RedirectResponse
    {
        $guest = $event->guests()->where('invitation_code', $code)->first();

        if (! $guest) {
            return redirect()->route('rsvp.login', $event)
                ->withErrors(['code' => 'El enlace de invitación no es válido o ha expirado.']);
        }
        $this->logInGuest($event, $guest->invitation_code);

        return redirect()->route('rsvp.index', $event);
    }

    public function index(Event $event): Factory|View|RedirectResponse
    {
        $code = session('guest_code');

        if (!$code) {
            return redirect()->route('rsvp.login', $event);
        }

        $guests = $event->guests()->where('invitation_code', $code)->get();

        if ($guests->isEmpty()) {
            return redirect()->route('rsvp.login', $event)
                ->withErrors(['code' => 'No se encontraron invitados con este código.']);
        }

        return view('rsvp', compact('event', 'guests'));
    }

    public function submit(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'guest_id' => 'required|integer',
            'attendance' => 'required|in:confirmed,declined',
            'dietary_restrictions' => 'nullable|string|max:500',
            'drink_preferences' => 'nullable|string|max:500',
        ]);

        $code = session('guest_code');

        $guest = $event->guests()
            ->where('invitation_code', $code)
            ->where('id', $validated['guest_id'])
            ->firstOrFail();

        $guest->update([
            'attendance' => $validated['attendance'],
            'dietary_restrictions' => $validated['dietary_restrictions'],
            'drink_preferences' => $validated['drink_preferences'],
        ]);

        $statusMsg = $validated['attendance'] === 'confirmed' ? 'confirmado/a' : 'actualizado/a';

        return back()->with('success', "¡{$guest->name} ha sido {$statusMsg}!");
    }

    public function logout(Event $event): RedirectResponse
    {
        session()->forget(['guest_code', 'guest_event_id']);
        return redirect()->route('rsvp.login', $event);
    }

    private function isGuestLoggedIn(Event $event): bool
    {
        return session('guest_event_id') === $event->id && session()->has('guest_code');
    }

    private function logInGuest(Event $event, string $code): void
    {
        session([
            'guest_code' => $code,
            'guest_event_id' => $event->id
        ]);
    }
}
