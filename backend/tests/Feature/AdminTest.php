<?php

namespace Tests\Feature;

use App\Enums\Disciplina;
use App\Enums\EstadoEnvio;
use App\Filament\Resources\Ciudades\Pages\ManageCiudades;
use App\Filament\Resources\Envios\Pages\ListEnvios;
use App\Filament\Resources\Envios\Pages\ViewEnvio;
use App\Filament\Resources\Eventos\Pages\CreateEvento;
use App\Filament\Resources\Eventos\Pages\ListEventos;
use App\Filament\Resources\Organizadores\Pages\ListOrganizadores;
use App\Filament\Resources\Organizadores\Pages\ViewOrganizador;
use App\Filament\Resources\Pais\Pages\ManagePais;
use App\Filament\Resources\Provincias\Pages\ManageProvincias;
use App\Models\Ciudad;
use App\Models\Envio;
use App\Models\Evento;
use App\Models\Organizador;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_un_visitante_sin_sesion_es_redirigido_al_login(): void
    {
        auth()->logout();

        $this->get('/admin')->assertRedirect('/admin/login');
    }

    /**
     * @return array<string, array{class-string}>
     */
    public static function listados(): array
    {
        return [
            'solicitudes' => [ListEnvios::class],
            'eventos' => [ListEventos::class],
            'organizadores' => [ListOrganizadores::class],
            'países' => [ManagePais::class],
            'provincias' => [ManageProvincias::class],
            'ciudades' => [ManageCiudades::class],
        ];
    }

    public function test_las_paginas_del_panel_responden(): void
    {
        $envio = Envio::factory()->create();
        $organizador = Organizador::factory()->create();

        foreach (['/admin', '/admin/solicitudes', "/admin/solicitudes/{$envio->id}", "/admin/eventos/create?envio={$envio->id}", '/admin/organizadores', "/admin/organizadores/{$organizador->id}", '/admin/ciudades', '/admin/provincias', '/admin/paises'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    #[DataProvider('listados')]
    public function test_los_listados_cargan(string $pagina): void
    {
        Livewire::test($pagina)->assertOk();
    }

    public function test_solicitudes_muestra_las_pendientes_por_defecto(): void
    {
        $pendiente = Envio::factory()->create();
        $descartada = Envio::factory()->create(['estado' => EstadoEnvio::Descartado]);

        Livewire::test(ListEnvios::class)
            ->assertCanSeeTableRecords([$pendiente])
            ->assertCanNotSeeTableRecords([$descartada]);
    }

    public function test_crear_evento_desde_una_solicitud_precarga_los_datos_y_la_marca_como_cargada(): void
    {
        $ciudad = Ciudad::factory()->create(['nombre' => 'Rosario']);
        $organizador = Organizador::factory()->create(['nombre' => 'Rolleros del Sur']);
        $envio = Envio::factory()->create([
            'evento' => 'Rolleada por el río',
            'disciplinas' => ['Rollers', 'Skate'],
            'tipo' => 'Salida grupal',
            'fecha' => '2026-11-14',
            'hora' => '19:00',
            'lugar' => 'Monumento a la Bandera',
            'ciudad' => 'rosario',
            'organiza' => 'Rolleros del Sur',
            'link' => 'https://instagram.com/rollerosdelsur?igsh=x',
            'detalles' => 'Traer casco',
        ]);

        Livewire::withQueryParams(['envio' => $envio->id])
            ->test(CreateEvento::class)
            ->assertSchemaStateSet([
                'titulo' => 'Rolleada por el río',
                'disciplinas' => [Disciplina::Rollers, Disciplina::Skate],
                'tipo' => 'salida',
                'fecha' => '2026-11-14',
                'lugar_nombre' => 'Monumento a la Bandera',
                'ciudad_id' => $ciudad->id,
                'organizador_id' => $organizador->id,
                'contacto_instagram' => 'rollerosdelsur',
                'descripcion' => 'Traer casco',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $evento = Evento::sole();
        $this->assertSame('rolleada-por-el-rio', $evento->slug);
        $this->assertTrue($evento->envio->is($envio));
        $this->assertSame(EstadoEnvio::Cargado, $envio->fresh()->estado);
    }

    public function test_crear_evento_exige_ciudad_lugar_y_disciplina(): void
    {
        Livewire::test(CreateEvento::class)
            ->fillForm(['titulo' => 'Jam sin datos', 'tipo' => 'jam', 'fecha' => '2026-11-01'])
            ->call('create')
            ->assertHasFormErrors(['ciudad_id' => 'required', 'lugar_nombre' => 'required', 'disciplinas' => 'required']);

        $this->assertDatabaseEmpty('eventos');
    }

    public function test_descartar_y_reabrir_una_solicitud(): void
    {
        $envio = Envio::factory()->create();

        Livewire::test(ViewEnvio::class, ['record' => $envio->getRouteKey()])
            ->callAction('descartar');
        $this->assertSame(EstadoEnvio::Descartado, $envio->fresh()->estado);

        Livewire::test(ViewEnvio::class, ['record' => $envio->getRouteKey()])
            ->assertActionHidden('crearEvento')
            ->callAction('reabrir');
        $this->assertSame(EstadoEnvio::Pendiente, $envio->fresh()->estado);
    }

    public function test_la_ficha_del_organizador_carga_con_sus_eventos(): void
    {
        $organizador = Organizador::factory()->create();
        Evento::factory()->for($organizador)->create();

        Livewire::test(ViewOrganizador::class, ['record' => $organizador->getRouteKey()])
            ->assertOk()
            ->assertSee($organizador->nombre);
    }

    public function test_los_slugs_de_eventos_no_se_repiten(): void
    {
        $primero = Evento::factory()->create(['titulo' => 'Skate Jam']);
        $segundo = Evento::factory()->create(['titulo' => 'Skate Jam']);

        $this->assertSame('skate-jam', $primero->slug);
        $this->assertSame('skate-jam-2', $segundo->slug);
    }
}
