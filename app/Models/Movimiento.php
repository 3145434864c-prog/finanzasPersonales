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


}
