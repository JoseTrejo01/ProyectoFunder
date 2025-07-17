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
        Schema::create('indicador_generos', function (Blueprint $table) {
    $table->id();
    $table->string('nombre_caja_rural');
    $table->string('departamento');
    $table->string('municipio');
    $table->string('comunidad');
    $table->string('nombre_apellidos');
    $table->string('sexo')->nullable();
    $table->string('etnia');
    $table->date('fecha_nacimiento');
    $table->integer('edad');
    $table->string('identidad');
    $table->string('cargo');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicador_generos');
    }
};
