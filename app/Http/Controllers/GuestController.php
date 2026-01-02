<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuestController extends Controller
{

    public function index(Event $event): Factory|View
    {
        $guests = $event->guests()->latest()->get();

        return view('dashboard.guests.index', compact('event', 'guests'));
    }

    public function store(Request $request, Event $event): RedirectResponse
    {
        // TODO: @Refactor - Modificar esto para utilizar LaravelData (DTO)
        // igual que en RsvpController para mantener consistencia y limpieza.
        // Issue detectado el: 2026-01-01

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'invitation_code' => 'nullable|string|max:20',
        ]);

        $code = $validated['invitation_code'];

        if (empty($code)) {
            // TODO: Hacer este prefijo dinámico basado en el tipo de evento (Event Type).
            // Por ejemplo: CUMPLE, FIESTA, CORPORATIVO.
            // Por ahora, para el MVP de casamiento, usamos 'BODA' fijo.
            $prefix = 'BODA';

            // Generamos 4 números aleatorios
            $random = rand(1000, 9999);
            $code = "{$prefix}-{$random}";

            while($event->guests()->where('invitation_code', $code)->exists()) {
                $random = rand(1000, 9999);
                $code = "{$prefix}-{$random}";
            }
        }

        $event->guests()->create([
            'name' => $validated['name'],
            'invitation_code' => $code,
            'attendance' => 'pending'
        ]);

        return back()->with('success', "¡Invitado agregado al grupo {$code}!");
    }

    /**
     * Elimina un invitado.
     */
    public function destroy(Event $event, Guest $guest): RedirectResponse
    {
        $guest->delete();
        return back()->with('success', 'Invitado eliminado correctamente.');
    }
}
