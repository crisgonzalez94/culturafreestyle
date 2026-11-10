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
        Schema::create('envios', function (Blueprint $table) {
            $table->id();
            $table->string('evento', 160);
            $table->json('disciplinas');
            $table->string('tipo', 60)->nullable();
            $table->date('fecha');
            $table->time('hora')->nullable();
            $table->boolean('se_repite')->default(false);
            $table->string('lugar', 200);
            $table->string('ciudad', 100)->nullable();
            $table->string('organiza', 160)->nullable();
            $table->string('link', 500)->nullable();
            $table->text('detalles')->nullable();
            $table->string('estado', 20)->default('pendiente')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('envios');
    }
};
