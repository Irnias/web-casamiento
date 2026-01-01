<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Feature;
use App\Models\Guest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PhotoUploadTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function un_invitado_puede_subir_una_foto_si_el_modulo_esta_activo(): void
    {
        Storage::fake('public');

        $event = Event::factory()->create(['slug' => 'boda-test']);
        $feature = Feature::create(['key' => 'photos', 'name' => 'Fotos']);
        $event->features()->attach($feature);

        $guest = Guest::factory()->create([
            'event_id' => $event->id,
            'invitation_code' => 'INVITADO-1'
        ]);

        $this->withSession([
            'guest_code' => 'INVITADO-1',
            'guest_event_id' => $event->id
        ]);

        $file = UploadedFile::fake()->image('recuerdo.jpg');

        $response = $this->post(route('photos.store', $event), [
            'photo' => $file,
            'message' => '¡Qué gran fiesta!'
        ]);

        $response->assertRedirect();

        $path = "events/{$event->slug}/photos/" . $file->hashName();
        Storage::disk('public')->assertExists($path);

        $this->assertDatabaseHas('photos', [
            'event_id' => $event->id,
            'status' => 'pending',
            'message' => '¡Qué gran fiesta!',
            'uploaded_by_guest_id' => $guest->id
        ]);
    }

    #[Test]
    public function no_se_puede_subir_foto_si_el_modulo_no_esta_activo(): void
    {
        Storage::fake('public');

        $event = Event::factory()->create();
        $guest = Guest::factory()->create(['event_id' => $event->id]);

        $this->withSession([
            'guest_code' => $guest->invitation_code,
            'guest_event_id' => $event->id
        ]);

        $response = $this->post(route('photos.store', $event), [
            'photo' => UploadedFile::fake()->image('test.jpg')
        ]);

        $response->assertStatus(500);

        Storage::disk('public')->assertDirectoryEmpty("events/{$event->slug}/photos");
    }
}
