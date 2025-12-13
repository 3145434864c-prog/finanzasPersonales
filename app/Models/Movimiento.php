<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
 
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
        static::created(function ($movimiento) {
            if ($movimiento->tipo === 'gasto') {
                // Buscar el presupuesto correspondiente usando la fecha del movimiento
                $mes = \Carbon\Carbon::parse($movimiento->fecha)->month;
                $anio = \Carbon\Carbon::parse($movimiento->fecha)->year;

                $presupuesto = Presupuesto::where('user_id', $movimiento->user_id)
                    ->where('categoria_id', $movimiento->categoria_id)
                    ->where('mes', $mes)
                    ->where('anio', $anio)
                    ->first();

                // Si existe el presupuesto, actualizar el monto gastado
                if ($presupuesto) {
                    $presupuesto->monto_gastado += $movimiento->monto;
                    $presupuesto->save();
                }
            }
        });

        static::updated(function ($movimiento) {
            Log::info('Movimiento updated', [
                'id' => $movimiento->id,
                'original_tipo' => $movimiento->getOriginal('tipo'),
                'new_tipo' => $movimiento->tipo,
                'original_monto' => $movimiento->getOriginal('monto'),
                'new_monto' => $movimiento->monto,
                'original_fecha' => $movimiento->getOriginal('fecha'),
                'new_fecha' => $movimiento->fecha,
            ]);

            $originalTipo = $movimiento->getOriginal('tipo');
            $originalMonto = $movimiento->getOriginal('monto');
            $originalCategoriaId = $movimiento->getOriginal('categoria_id');
            $originalFecha = $movimiento->getOriginal('fecha');

            $newTipo = $movimiento->tipo;
            $newMonto = $movimiento->monto;
            $newCategoriaId = $movimiento->categoria_id;
            $newFecha = $movimiento->fecha;

            // Calcular mes y año originales y nuevos
            $originalMes = \Carbon\Carbon::parse($originalFecha)->month;
            $originalAnio = \Carbon\Carbon::parse($originalFecha)->year;
            $newMes = \Carbon\Carbon::parse($newFecha)->month;
            $newAnio = \Carbon\Carbon::parse($newFecha)->year;

            Log::info('Meses calculados', [
                'original_mes' => $originalMes,
                'original_anio' => $originalAnio,
                'new_mes' => $newMes,
                'new_anio' => $newAnio,
            ]);

            // Determinar si el presupuesto cambió
            $presupuestoCambio = !(
                $originalCategoriaId == $newCategoriaId &&
                $originalMes == $newMes &&
                $originalAnio == $newAnio
            );

            if ($presupuestoCambio) {
                // Si cambió el presupuesto, restar del original y sumar al nuevo
                if ($originalTipo === 'gasto') {
                    $presupuestoOriginal = Presupuesto::where('user_id', $movimiento->user_id)
                        ->where('categoria_id', $originalCategoriaId)
                        ->where('mes', $originalMes)
                        ->where('anio', $originalAnio)
                        ->first();

                    Log::info('Presupuesto original encontrado', ['presupuesto' => $presupuestoOriginal ? $presupuestoOriginal->toArray() : null]);

                    if ($presupuestoOriginal) {
                        $presupuestoOriginal->monto_gastado -= $originalMonto;
                        $presupuestoOriginal->save();
                        Log::info('Presupuesto original actualizado', ['monto_gastado' => $presupuestoOriginal->monto_gastado]);
                    }
                }

                if ($newTipo === 'gasto') {
                    $presupuestoNuevo = Presupuesto::where('user_id', $movimiento->user_id)
                        ->where('categoria_id', $newCategoriaId)
                        ->where('mes', $newMes)
                        ->where('anio', $newAnio)
                        ->first();

                    Log::info('Presupuesto nuevo encontrado', ['presupuesto' => $presupuestoNuevo ? $presupuestoNuevo->toArray() : null]);

                    if ($presupuestoNuevo) {
                        $presupuestoNuevo->monto_gastado += $newMonto;
                        $presupuestoNuevo->save();
                        Log::info('Presupuesto nuevo actualizado', ['monto_gastado' => $presupuestoNuevo->monto_gastado]);
                    }
                }
            } else {
                // Si es el mismo presupuesto, solo ajustar la diferencia
                $diferencia = $newMonto - $originalMonto;
                Log::info('Mismo presupuesto, ajustando diferencia', ['diferencia' => $diferencia]);

                if ($originalTipo === 'gasto' && $newTipo === 'gasto') {
                    // Ambos son gasto, ajustar diferencia
                    $presupuesto = Presupuesto::where('user_id', $movimiento->user_id)
                        ->where('categoria_id', $newCategoriaId)
                        ->where('mes', $newMes)
                        ->where('anio', $newAnio)
                        ->first();

                    Log::info('Presupuesto encontrado para ajuste', ['presupuesto' => $presupuesto ? $presupuesto->toArray() : null]);

                    if ($presupuesto) {
                        $presupuesto->monto_gastado += $diferencia;
                        $presupuesto->save();
                        Log::info('Presupuesto ajustado', ['monto_gastado' => $presupuesto->monto_gastado]);
                    }
                } elseif ($originalTipo === 'gasto' && $newTipo !== 'gasto') {
                    // Cambió de gasto a no gasto, restar el original
                    $presupuesto = Presupuesto::where('user_id', $movimiento->user_id)
                        ->where('categoria_id', $newCategoriaId)
                        ->where('mes', $newMes)
                        ->where('anio', $newAnio)
                        ->first();

                    if ($presupuesto) {
                        $presupuesto->monto_gastado -= $originalMonto;
                        $presupuesto->save();
                        Log::info('Cambio de gasto a no gasto, presupuesto actualizado', ['monto_gastado' => $presupuesto->monto_gastado]);
                    }
                } elseif ($originalTipo !== 'gasto' && $newTipo === 'gasto') {
                    // Cambió de no gasto a gasto, sumar el nuevo
                    $presupuesto = Presupuesto::where('user_id', $movimiento->user_id)
                        ->where('categoria_id', $newCategoriaId)
                        ->where('mes', $newMes)
                        ->where('anio', $newAnio)
                        ->first();

                    if ($presupuesto) {
                        $presupuesto->monto_gastado += $newMonto;
                        $presupuesto->save();
                        Log::info('Cambio de no gasto a gasto, presupuesto actualizado', ['monto_gastado' => $presupuesto->monto_gastado]);
                    }
                }
            }
        });

        static::deleted(function ($movimiento) {
            Log::info('Movimiento deleted', [
                'id' => $movimiento->id,
                'tipo' => $movimiento->tipo,
                'monto' => $movimiento->monto,
                'fecha' => $movimiento->fecha,
                'categoria_id' => $movimiento->categoria_id,
                'user_id' => $movimiento->user_id,
            ]);

            if ($movimiento->tipo === 'gasto') {
                // Buscar el presupuesto correspondiente usando la fecha del movimiento
                $mes = \Carbon\Carbon::parse($movimiento->fecha)->month;
                $anio = \Carbon\Carbon::parse($movimiento->fecha)->year;

                Log::info('Buscando presupuesto para restar', [
                    'user_id' => $movimiento->user_id,
                    'categoria_id' => $movimiento->categoria_id,
                    'mes' => $mes,
                    'anio' => $anio,
                ]);

                $presupuesto = Presupuesto::where('user_id', $movimiento->user_id)
                    ->where('categoria_id', $movimiento->categoria_id)
                    ->where('mes', $mes)
                    ->where('anio', $anio)
                    ->first();

                Log::info('Presupuesto encontrado para restar', ['presupuesto' => $presupuesto ? $presupuesto->toArray() : null]);

                // Si existe el presupuesto, restar el monto gastado
                if ($presupuesto) {
                    $presupuesto->monto_gastado -= $movimiento->monto;
                    $presupuesto->save();
                    Log::info('Presupuesto actualizado después de eliminar', ['monto_gastado' => $presupuesto->monto_gastado]);
                }
            }
        });
    }

}
