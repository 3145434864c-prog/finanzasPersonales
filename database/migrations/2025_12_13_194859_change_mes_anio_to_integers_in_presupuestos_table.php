<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convertir mes de string a integer (1-12)
        DB::statement("UPDATE presupuestos SET mes = CASE
            WHEN mes = 'Enero' THEN 1
            WHEN mes = 'Febrero' THEN 2
            WHEN mes = 'Marzo' THEN 3
            WHEN mes = 'Abril' THEN 4
            WHEN mes = 'Mayo' THEN 5
            WHEN mes = 'Junio' THEN 6
            WHEN mes = 'Julio' THEN 7
            WHEN mes = 'Agosto' THEN 8
            WHEN mes = 'Septiembre' THEN 9
            WHEN mes = 'Octubre' THEN 10
            WHEN mes = 'Noviembre' THEN 11
            WHEN mes = 'Diciembre' THEN 12
            ELSE 1 END");

        // Convertir anio de string a integer
        DB::statement("UPDATE presupuestos SET anio = CAST(anio AS UNSIGNED)");

        Schema::table('presupuestos', function (Blueprint $table) {
            $table->integer('mes')->change();
            $table->integer('anio')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->string('mes')->change();
            $table->string('anio')->change();
        });
    }
};
