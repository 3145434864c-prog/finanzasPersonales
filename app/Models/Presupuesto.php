<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    protected $fillable = [
        'user_id',
        'categoria_id',
        'monto_asignado',
        'monto_gastado',
        'mes',
        'anio',
        'fecha_limite',
    ];

    protected $casts = [
        'fecha_limite' => 'date',
    ];

    //relacion de muchos a uno con el modelo usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //relacion de muchos a uno con el modelo categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('fecha_limite')
              ->orWhere('fecha_limite', '>=', now()->startOfDay());
        });
    }
}
