<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Event;
use App\Models\Feature;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles y Permisos primero
        $this->call(RoleSeeder::class);

        // 2. Features
        $featRsvp = Feature::firstOrCreate(['key' => 'rsvp'], ['name' => 'Confirmación de Asistencia']);
        $featPhotos = Feature::firstOrCreate(['key' => 'photos'], ['name' => 'Galería de Fotos']);
        $featSongs = Feature::firstOrCreate(['key' => 'songs'], ['name' => 'Lista de Canciones']);

        // 3. Crear Boda Rami & Meli
        $boda = Event::create([
            'name' => 'Boda Rami & Meli',
            'slug' => 'rami-meli',
            'event_date' => '2025-12-20 18:00:00',
        ]);

        $boda->settings()->createMany([
            ['key' => 'primary_color', 'value' => '#FF5733'],
            ['key' => 'welcome_message', 'value' => '¡Nos casamos!'],
        ]);

        $boda->features()->attach([$featRsvp->id, $featPhotos->id, $featSongs->id]);

        // Crear invitados vinculados AL EVENTO
        $boda->guests()->createMany([
            ['invitation_code' => 'FAMILIA-PEREZ', 'name' => 'Damián y Flor'],
            ['invitation_code' => 'TIO-ALBERTO', 'name' => 'Alberto'],
        ]);

        // ==========================================
        // 4. CREAR USUARIO ADMIN (TU LOGIN)
        // ==========================================
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            ['name' => 'Rami Admin', 'password' => bcrypt('password')]
        );

        // Buscar rol Dueño
        $ownerRole = Role::where('name', RoleEnum::OWNER->value)->first();

        // Vincular Usuario -> Evento -> Rol
        $boda->users()->syncWithoutDetaching([
            $adminUser->id => ['role_id' => $ownerRole->id]
        ]);
    }
}
