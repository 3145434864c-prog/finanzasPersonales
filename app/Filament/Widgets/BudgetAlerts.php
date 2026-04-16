<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Presupuesto;

class BudgetAlerts extends BaseWidget
{
    protected int|string|array $columnSpan = 12;

    protected static ?string $heading = 'Alertas de Presupuesto';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Presupuesto::where('mes', now()->month)->where('anio', now()->year)->with('categoria')
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
                    ->getStateUsing(fn (Presupuesto $record): float => $record->monto_asignado - $record->monto_gastado)
                    ->formatStateUsing(fn (float $state): string => $state >= 0 ? 'te queda ' . number_format($state, 0, ',', '.') . ' COP del presupuesto' : 'superaste por ' . number_format(abs($state), 0, ',', '.') . ' COP este presupuesto')
                    ->color(fn (float $state): string => $state < 0 ? 'danger' : 'success'),
            ])
            ->defaultSort('monto_gastado', 'desc');
    }
}
