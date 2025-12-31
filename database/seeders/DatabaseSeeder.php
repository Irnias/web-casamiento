<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Feature;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $featRsvp = Feature::firstOrCreate(
            ['key' => 'rsvp'],
            ['name' => 'Confirmación de Asistencia']
        );

        $featPhotos = Feature::firstOrCreate(
            ['key' => 'photos'],
            ['name' => 'Galería de Fotos']
        );

        $featSongs = Feature::firstOrCreate(
            ['key' => 'songs'],
            ['name' => 'Lista de Canciones']
        );

        $boda1 = Event::create([
            'name' => 'Boda Rami & Meli',
            'slug' => 'rami-meli',
            'event_date' => '2025-12-20 18:00:00',
        ]);

        $boda1->settings()->createMany([
            ['key' => 'primary_color', 'value' => '#FF5733'],
            ['key' => 'welcome_message', 'value' => '¡Nos casamos y queremos festejar con vos!'],
        ]);

        $boda1->features()->attach([$featRsvp->id, $featPhotos->id, $featSongs->id]);

        $boda1->guests()->createMany([
            ['invitation_code' => 'FAMILIA-PEREZ', 'name' => 'Damián y Flor'],
            ['invitation_code' => 'TIO-ALBERTO', 'name' => 'Alberto'],
        ]);

        $boda2 = Event::create([
            'name' => 'Cumple de Pepe',
            'slug' => 'cumple-pepe',
            'event_date' => '2026-01-10 21:00:00',
        ]);

        $boda2->settings()->create(['key' => 'primary_color', 'value' => '#0000FF']);

        $boda2->features()->attach([$featRsvp->id]);

        $boda2->guests()->create([
            'invitation_code' => 'FAMILIA-PEREZ',
            'name' => 'Los Pérez (Amigos de Pepe)',
        ]);
    }
}
