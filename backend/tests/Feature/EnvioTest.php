<?php

namespace Tests\Feature;

use App\Enums\EstadoEnvio;
use App\Models\Envio;
use App\Notifications\EnvioRecibido;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EnvioTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function datosValidos(array $cambios = []): array
    {
        return array_merge([
            'evento' => 'Rolleada nocturna',
            'disciplinas' => ['Rollers', 'Skate'],
            'fecha' => '2026-11-01',
            'hora' => '20:30',
            'se_repite' => true,
            'lugar' => 'Costanera Sur',
            'link' => 'https://instagram.com/rolleada',
        ], $cambios);
    }

    public function test_guarda_el_envio_como_pendiente_y_avisa_por_mail(): void
    {
        Notification::fake();
        config(['cultura.aviso_email' => 'aviso@example.com']);

        $response = $this->postJson(route('v1.envios.store'), $this->datosValidos());

        $response->assertCreated()
            ->assertJsonPath('data.evento', 'Rolleada nocturna')
            ->assertJsonPath('data.disciplinas', ['Rollers', 'Skate'])
            ->assertJsonPath('data.estado', 'pendiente');

        $envio = Envio::sole();
        $this->assertSame(EstadoEnvio::Pendiente, $envio->estado);
        $this->assertTrue($envio->se_repite);

        Notification::assertSentTo(
            new AnonymousNotifiable,
            EnvioRecibido::class,
            fn (EnvioRecibido $notificacion, array $canales, AnonymousNotifiable $destino) => $destino->routes['mail'] === 'aviso@example.com'
                && $notificacion->envio->is($envio),
        );
    }

    public function test_no_avisa_si_no_hay_mail_configurado(): void
    {
        Notification::fake();
        config(['cultura.aviso_email' => null]);

        $this->postJson(route('v1.envios.store'), $this->datosValidos())->assertCreated();

        Notification::assertNothingSent();
    }

    public function test_rechaza_envios_sin_los_campos_obligatorios(): void
    {
        $response = $this->postJson(route('v1.envios.store'), [
            'evento' => 'Sin datos',
            'fecha' => '01/11/2026',
            'link' => 'no-es-un-link',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['disciplinas', 'fecha', 'lugar', 'link']);
        $this->assertDatabaseEmpty('envios');
    }

    public function test_rechaza_envios_de_bots_que_completan_el_honeypot(): void
    {
        $this->postJson(route('v1.envios.store'), $this->datosValidos(['web' => 'https://spam.example']))
            ->assertUnprocessable();

        $this->assertDatabaseEmpty('envios');
    }

    public function test_limita_la_cantidad_de_envios_por_minuto(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->postJson(route('v1.envios.store'), $this->datosValidos())->assertCreated();
        }

        $this->postJson(route('v1.envios.store'), $this->datosValidos())->assertTooManyRequests();
    }
}
