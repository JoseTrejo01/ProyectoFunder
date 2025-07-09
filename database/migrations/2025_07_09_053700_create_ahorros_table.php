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
       Schema::create('ahorros', function (Blueprint $table) {
    $table->id();
    $table->string('nombre_caja_rural');

    // Socios
    $table->integer('socios_no');
    $table->decimal('socios_ahorros', 12, 2);
    $table->decimal('socios_promedio', 12, 2);

    // No Socios - Adultos
    $table->integer('adultos_no');
    $table->decimal('adultos_ahorros', 12, 2);
    $table->decimal('adultos_promedio', 12, 2);

    // No Socios - Niños
    $table->integer('ninos_no');
    $table->decimal('ninos_ahorros', 12, 2);
    $table->decimal('ninos_promedio', 12, 2);

    // Subtotal No Socios
    $table->integer('subtotal_no_socios_no');
    $table->decimal('subtotal_no_socios_ahorros', 12, 2);
    $table->decimal('subtotal_no_socios_promedio', 12, 2);

    // Total
    $table->integer('total_no');
    $table->decimal('total_ahorros', 12, 2);
    $table->decimal('total_promedio', 12, 2);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ahorros');
    }
};
