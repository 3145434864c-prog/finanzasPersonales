<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'nombre',
        'tipo',
    ];

    

    //relacion de muchos a uno con el modelo movimientos
    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }
}
