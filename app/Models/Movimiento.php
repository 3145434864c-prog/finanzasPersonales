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

                // Si existe el presupuesto, recalcular el monto gastado
                if ($presupuesto) {
                    $presupuesto->monto_gastado = Movimiento::where('user_id', $movimiento->user_id)
                        ->where('categoria_id', $movimiento->categoria_id)
                        ->where('tipo', 'gasto')
                        ->whereRaw('MONTH(fecha) = ?', [$mes])
                        ->whereRaw('YEAR(fecha) = ?', [$anio])
                        ->sum('monto');
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

            // Recalcular el presupuesto original si existía
            if ($originalTipo === 'gasto') {
                $presupuestoOriginal = Presupuesto::where('user_id', $movimiento->user_id)
                    ->where('categoria_id', $originalCategoriaId)
                    ->where('mes', $originalMes)
                    ->where('anio', $originalAnio)
                    ->first();

                if ($presupuestoOriginal) {
                    $presupuestoOriginal->monto_gastado = Movimiento::where('user_id', $movimiento->user_id)
                        ->where('categoria_id', $originalCategoriaId)
                        ->where('tipo', 'gasto')
                        ->whereRaw('MONTH(fecha) = ?', [$originalMes])
                        ->whereRaw('YEAR(fecha) = ?', [$originalAnio])
                        ->sum('monto');
                    $presupuestoOriginal->save();
                    Log::info('Presupuesto original recalculado', ['monto_gastado' => $presupuestoOriginal->monto_gastado]);
                }
            }

            // Recalcular el presupuesto nuevo si cambió o si es el mismo pero tipo cambió
            if ($newTipo === 'gasto' || $presupuestoCambio) {
                $presupuestoNuevo = Presupuesto::where('user_id', $movimiento->user_id)
                    ->where('categoria_id', $newCategoriaId)
                    ->where('mes', $newMes)
                    ->where('anio', $newAnio)
                    ->first();

                if ($presupuestoNuevo) {
                    $presupuestoNuevo->monto_gastado = Movimiento::where('user_id', $movimiento->user_id)
                        ->where('categoria_id', $newCategoriaId)
                        ->where('tipo', 'gasto')
                        ->whereRaw('MONTH(fecha) = ?', [$newMes])
                        ->whereRaw('YEAR(fecha) = ?', [$newAnio])
                        ->sum('monto');
                    $presupuestoNuevo->save();
                    Log::info('Presupuesto nuevo recalculado', ['monto_gastado' => $presupuestoNuevo->monto_gastado]);
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

                $presupuesto = Presupuesto::where('user_id', $movimiento->user_id)
                    ->where('categoria_id', $movimiento->categoria_id)
                    ->where('mes', $mes)
                    ->where('anio', $anio)
                    ->first();

                // Si existe el presupuesto, recalcular el monto gastado
                if ($presupuesto) {
                    $presupuesto->monto_gastado = Movimiento::where('user_id', $movimiento->user_id)
                        ->where('categoria_id', $movimiento->categoria_id)
                        ->where('tipo', 'gasto')
                        ->whereRaw('MONTH(fecha) = ?', [$mes])
                        ->whereRaw('YEAR(fecha) = ?', [$anio])
                        ->sum('monto');
                    $presupuesto->save();
                    Log::info('Presupuesto recalculado después de eliminar', ['monto_gastado' => $presupuesto->monto_gastado]);
                }
            }
        });
    }

}
