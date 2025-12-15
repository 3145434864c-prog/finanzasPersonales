<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Movimiento;

class RecentMovimientos extends BaseWidget
{
    protected int|string|array $columnSpan = 12;

    protected static ?string $heading = 'Movimientos Recientes';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Movimiento::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date(),
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ingreso' => 'success',
                        'gasto' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->html()
                    ->limit(30),
                Tables\Columns\TextColumn::make('monto')
                    ->label('Monto')
                    ->money('PEN'),
                Tables\Columns\TextColumn::make('categoria.nombre')
                    ->label('Categoría'),
            ]);
    }
}
