<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Guest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function un_invitado_no_puede_entrar_a_otro_evento_con_su_codigo(): void
    {
        $eventoA = Event::factory()->create(['slug' => 'rami-meli']);
        Guest::factory()->create([
            'event_id' => $eventoA->id,
            'invitation_code' => 'FAMILIA-PEREZ'
        ]);

        $eventoB = Event::factory()->create(['slug' => 'cumple-pepe']);
        $response = $this->post(route('login.process', $eventoB), [
            'code' => 'FAMILIA-PEREZ'
        ]);

        $response->assertSessionHasErrors('code');
        $response->assertSessionMissing('guest_code');
    }

    #[Test]
    public function el_login_exitoso_redirige_a_la_invitacion_correcta(): void
    {
        $evento = Event::factory()->create(['slug' => 'mi-boda']);
        Guest::factory()->create([
            'event_id' => $evento->id,
            'invitation_code' => 'TEST-VIP'
        ]);

        $response = $this->post(route('login.process', $evento), [
            'code' => 'test-vip'
        ]);

        $response->assertRedirect(route('rsvp.index', $evento));
        $this->assertEquals($evento->id, session('guest_event_id'));
    }

    #[Test]
    public function entrar_a_una_url_de_evento_inexistente_da_404(): void
    {
        $response = $this->get('/evento-fantasma');

        $response->assertNotFound();
    }
}
