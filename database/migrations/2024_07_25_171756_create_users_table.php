<?php

// database/migrations/YYYY_MM_DD_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Genera una columna `id` auto-incrementable
            $table->string('name'); // Nombre del usuario
            $table->string('email')->unique(); // Email único para cada usuario
            $table->timestamp('email_verified_at')->nullable(); // Verificación de email
            $table->string('password'); // Contraseña
            $table->rememberToken(); // Token para recordar sesión
            $table->timestamps(); // `created_at` y `updated_at` columnas
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
