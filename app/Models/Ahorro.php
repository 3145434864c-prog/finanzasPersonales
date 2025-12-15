<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ahorro extends Model
{
    protected $fillable = [
        'tipo_ahorro',
        'descripcion',
        'monto_ahorrado',
        'referencia_tipo',
        'referencia_id',
        'nombre_meta',
        'monto_objetivo',
        'periodicidad',
        'monto_aporte',
        'fecha_inicio',
        'fecha_objetivo',
        'estado',
    ];

    protected $casts = [
        'monto_ahorrado' => 'decimal:2',
        'monto_objetivo' => 'decimal:2',
        'monto_aporte' => 'decimal:2',
        'fecha_inicio' => 'date',
        'fecha_objetivo' => 'date',
    ];

    protected $attributes = [
        'monto_ahorrado' => 0,
    ];
}
