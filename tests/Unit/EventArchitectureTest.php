<?php

namespace Tests\Unit;

use App\Exceptions\FeatureNotActiveException;
use App\Exceptions\SettingNotFoundException;
use App\Models\Event;
use App\Models\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventArchitectureTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function devuelve_el_valor_del_setting_si_existe(): void
    {
        $event = Event::factory()->create(['name' => 'Test Event']);

        $event->settings()->create([
            'key' => 'primary_color',
            'value' => '#FF0000'
        ]);

        $this->assertEquals('#FF0000', $event->getSetting('primary_color'));
    }

    #[Test]
    public function explota_con_excepcion_propia_si_falta_setting(): void
    {
        $event = Event::factory()->create();

        $this->expectException(SettingNotFoundException::class);
        $this->expectExceptionMessage("La configuración obligatoria 'font_family' no fue encontrada");

        $event->getSetting('font_family');
    }

    #[Test]
    public function valida_correctamente_si_tiene_features_activas(): void
    {
        $event = Event::factory()->create();
        $feature = Feature::create(['key' => 'photos', 'name' => 'Fotos']);

        $this->assertFalse($event->hasFeature('photos'));
        $event->features()->attach($feature);

        $event->load('features');
        $this->assertTrue($event->hasFeature('photos'));
    }

    #[Test]
    public function require_feature_lanza_excepcion_si_no_la_tiene(): void
    {
        $event = Event::factory()->create();

        $this->expectException(FeatureNotActiveException::class);

        $event->requireFeature('songs');
    }
}
