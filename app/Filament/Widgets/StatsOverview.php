<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Presupuesto;
use App\Models\Movimiento;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Presupuestos', Presupuesto::count())
                ->description('Número total de presupuestos')
                ->descriptionIcon('heroicon-m-chart-pie')
                ->color('success'),

            Stat::make('Total Movimientos', Movimiento::count())
                ->description('Número total de movimientos')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),

            Stat::make('Monto Total Asignado', 'S/ ' . number_format(Presupuesto::sum('monto_asignado'), 2))
                ->description('Suma de todos los montos asignados')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),

            Stat::make('Monto Total Gastado', 'S/ ' . number_format(Presupuesto::sum('monto_gastado'), 2))
                ->description('Suma de todos los montos gastados')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('danger'),
        ];
    }
}
