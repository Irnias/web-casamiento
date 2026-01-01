<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Photo;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Listar fotos (Galería)
     */
    public function index(Event $event): Factory|View
    {
        $event->requireFeature('photos');
        $photos = $event->photos()
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('photos.index', compact('event', 'photos'));
    }

    /**
     * Procesar subida
     */
    public function store(Request $request, Event $event): RedirectResponse
    {
        $event->requireFeature('photos');

        $request->validate([
            'photo' => ['required', 'image', 'max:10240'],
            'message' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('photo');

        $path = $file->store(
            "events/{$event->slug}/photos",
            'public'
        );

        $event->photos()->create([
            'file_path' => $path,
            'source' => 'guest',
            'status' => 'pending',
            'uploaded_by_guest_id' => $this->getGuestIdFromSession($event),
            'message' => $request->input('message')
        ]);

        return back()->with('success', '¡Foto subida! Aparecerá en la galería cuando los novios la aprueben.');
    }

    private function getGuestIdFromSession(Event $event)
    {
        $guest = $event->guests()->where('invitation_code', session('guest_code'))->first();
        return $guest?->id;
    }
}
