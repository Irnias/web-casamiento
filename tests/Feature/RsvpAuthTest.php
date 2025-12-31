<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Guest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RsvpAuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function un_invitado_puede_ver_la_pantalla_de_login_del_evento(): void
    {
        $event = Event::factory()->create();

        $response = $this->get(route('login', $event));

        $response->assertStatus(200);
        $response->assertSee('Ingresar');
    }

    #[Test]
    public function un_codigo_valido_inicia_sesion_y_redirige(): void
    {
        $event = Event::factory()->create();

        Guest::factory()->create([
            'event_id' => $event->id,
            'invitation_code' => 'BODA-TEST',
            'name' => 'Tester'
        ]);

        $response = $this->post(route('login.process', $event), [
            'code' => 'boda-test'
        ]);

        $response->assertRedirect(route('rsvp.index', $event));
        $response->assertSessionHas('guest_code', 'BODA-TEST');
    }

    #[Test]
    public function un_codigo_invalido_muestra_error(): void
    {
        $event = Event::factory()->create();

        $response = $this->post(route('login.process', $event), [
            'code' => 'CODIGO-FALSO'
        ]);

        $response->assertSessionHasErrors('code');
    }

    #[Test]
    public function no_se_puede_entrar_a_la_invitacion_sin_loguearse(): void
    {
        $event = Event::factory()->create();

        $response = $this->get(route('rsvp.index', $event));
        $response->assertRedirect(route('login', $event));
    }
}
