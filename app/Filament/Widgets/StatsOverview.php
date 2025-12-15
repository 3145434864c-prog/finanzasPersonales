<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Presupuesto;
use App\Models\Movimiento;

class StatsOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 12;

    protected function getStats(): array
    {
        // Optimized single query to get all aggregated data
        $budgetData = Presupuesto::selectRaw('
            SUM(monto_asignado) as total_asignado,
            SUM(monto_gastado) as total_gastado,
            SUM(CASE WHEN mes = ? AND anio = ? THEN monto_gastado ELSE 0 END) as current_month_spent,
            SUM(CASE WHEN mes = ? AND anio = ? THEN monto_gastado ELSE 0 END) as prev_month_spent
        ', [
            now()->month,
            now()->year,
            now()->month == 1 ? 12 : now()->month - 1,
            now()->month == 1 ? now()->year - 1 : now()->year
        ])->first();

        $totalAsignado = $budgetData->total_asignado ?? 0;
        $totalGastado = $budgetData->total_gastado ?? 0;
        $currentMonthSpent = $budgetData->current_month_spent ?? 0;
        $prevMonthSpent = $budgetData->prev_month_spent ?? 0;

        $savingsRate = $totalAsignado > 0 ? round((($totalAsignado - $totalGastado) / $totalAsignado) * 100, 1) : 0;
        $expenseRatio = $totalAsignado > 0 ? round(($totalGastado / $totalAsignado) * 100, 1) : 0;
        $monthlyTrend = $prevMonthSpent > 0 ? round((($currentMonthSpent - $prevMonthSpent) / $prevMonthSpent) * 100, 1) : 0;

        return [
            Stat::make('Total Presupuestos', Presupuesto::count())
                ->description('Número total de presupuestos activos')
                ->descriptionIcon('heroicon-m-chart-pie')
                ->color('success'),

            Stat::make('Total Movimientos', Movimiento::count())
                ->description('Número total de movimientos registrados')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),

            Stat::make('Tasa de Ahorro', $savingsRate . '%')
                ->description('Porcentaje de presupuesto no gastado')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color($savingsRate >= 20 ? 'success' : ($savingsRate >= 10 ? 'warning' : 'danger')),

            Stat::make('Ratio de Gastos', $expenseRatio . '%')
                ->description('Porcentaje del presupuesto utilizado')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color($expenseRatio <= 80 ? 'success' : ($expenseRatio <= 95 ? 'warning' : 'danger')),

            Stat::make('Tendencia Mensual', ($monthlyTrend >= 0 ? '+' : '') . $monthlyTrend . '%')
                ->description('Cambio en gastos vs mes anterior')
                ->descriptionIcon($monthlyTrend <= 0 ? 'heroicon-o-arrow-trending-down' : 'heroicon-o-arrow-trending-up')
                ->color($monthlyTrend <= 0 ? 'success' : 'danger'),

            Stat::make('Saldo Disponible', 'S/ ' . number_format($totalAsignado - $totalGastado, 2))
                ->description('Actualmente es tu presupuesto total disponible')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color(($totalAsignado - $totalGastado) >= 0 ? 'success' : 'danger'),
        ];
    }
}
