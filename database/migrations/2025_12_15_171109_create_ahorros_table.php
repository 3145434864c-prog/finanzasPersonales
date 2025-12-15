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
        $table->string('tipo_ahorro');
        $table->text('descripcion')->nullable();
        $table->decimal('monto_ahorrado', 12, 2);
        $table->date('fecha');
        $table->string('referencia_tipo')->nullable();
        $table->unsignedBigInteger('referencia_id')->nullable();
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

