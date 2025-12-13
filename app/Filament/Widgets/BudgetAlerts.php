<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Presupuesto;

class BudgetAlerts extends BaseWidget
{
    protected static ?string $heading = 'Alertas de Presupuesto';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Presupuesto::where('monto_gastado', '>', 'monto_asignado')->with('categoria')
            )
            ->columns([
                Tables\Columns\TextColumn::make('categoria.nombre')
                    ->label('Categoría')
                    ->sortable(),
                Tables\Columns\TextColumn::make('monto_asignado')
                    ->label('Monto Asignado')
                    ->money('COP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('monto_gastado')
                    ->label('Monto Gastado')
                    ->money('COP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('diferencia')
                    ->label('Diferencia (Asignado - Gastado)')
                    ->money('COP')
                    ->getStateUsing(fn (Presupuesto $record): float => $record->monto_asignado - $record->monto_gastado)
                    ->color(fn (float $state): string => $state < 0 ? 'danger' : 'success'),
            ])
            ->defaultSort('monto_gastado', 'desc');
    }
}
