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
        Schema::table('ahorros', function (Blueprint $table) {
            $table->string('nombre_meta');
            $table->decimal('monto_objetivo', 12, 2);
            $table->enum('periodicidad', ['diario', 'semanal', 'mensual']);
            $table->decimal('monto_aporte', 12, 2);
            $table->date('fecha_inicio');
            $table->date('fecha_objetivo');
            $table->string('estado')->default('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ahorros', function (Blueprint $table) {
            $table->dropColumn(['nombre_meta', 'monto_objetivo', 'periodicidad', 'monto_aporte', 'fecha_inicio', 'fecha_objetivo', 'estado']);
        });
    }
};
