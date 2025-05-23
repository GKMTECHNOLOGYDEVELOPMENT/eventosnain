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
        Schema::create('cliente', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nombre');
            $table->string('empresa')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email')->nullable()->unique('email');
            $table->string('tipo_cliente')->nullable();
            $table->string('servicios')->nullable();
            $table->text('mensaje')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente');
    }
};
