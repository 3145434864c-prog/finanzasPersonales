<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $fillable = [
        'user_id',
        'categoria_id',
        'tipo',
        'monto',
        'descripcion',
        'foto',
        'fecha',
    ];

    //relacion  de muchos a uno con el modelo de usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //relacion  de muchos a uno con el modelo de categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    protected static function booted()
    {
        static::created(function ($movimiento){
            if ($movimiento->tipo === 'gasto') {
            //Buscar el presupuesto correspondiente
            $presupuesto = Presupuesto::where('user_id', $movimiento->user_id)
                ->where('categoria_id', $movimiento->categoria_id)
                ->where('mes', now()->format('F')) //usar el mes actual
                ->where('anio', now()->year)
                ->first();

                //si existe el presupuesto, actualizar el monto gastado 
                if($presupuesto){
                    $presupuesto->monto_gastado += $movimiento->monto;
                    $presupuesto->save();
                }

            }
        });
    }

}
