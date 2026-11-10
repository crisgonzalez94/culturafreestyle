<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 160);
            $table->string('slug', 180)->unique();
            $table->json('disciplinas');
            $table->string('tipo', 60);
            $table->json('etiquetas')->nullable();

            $table->date('fecha');
            $table->date('fecha_fin')->nullable();
            $table->string('hora', 5)->nullable();
            $table->string('hora_fin', 5)->nullable();
            $table->string('repite_frecuencia', 20)->nullable();
            $table->date('repite_hasta')->nullable();

            $table->string('lugar_nombre', 200);
            $table->string('lugar_direccion', 200)->nullable();
            $table->foreignId('ciudad_id')->constrained('ciudades')->restrictOnDelete();
            $table->string('mapa', 500)->nullable();

            $table->foreignId('organizador_id')->nullable()->constrained('organizadores')->nullOnDelete();
            $table->string('contacto_instagram', 60)->nullable();
            $table->string('contacto_whatsapp', 30)->nullable();
            $table->string('contacto_web', 500)->nullable();
            $table->string('inscripcion', 500)->nullable();

            $table->json('niveles')->nullable();
            $table->string('costo', 100)->default('Gratis');
            $table->string('estado', 20)->default('confirmado');
            $table->boolean('destacado')->default(false);
            $table->string('imagen')->nullable();
            $table->json('datos')->nullable();
            $table->text('descripcion')->nullable();

            $table->foreignId('envio_id')->nullable()->constrained('envios')->nullOnDelete();
            $table->timestamps();

            $table->index('fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
