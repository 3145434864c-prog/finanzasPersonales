<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Presupuesto;
use App\Models\Movimiento;
use App\Models\Ahorro;

class StatsOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 12;

    protected function getStats(): array
    {
        // Optimized single query to get all aggregated data
        $budgetData = Presupuesto::active()->selectRaw('
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

        $savingsData = Ahorro::selectRaw('SUM(monto_ahorrado) as total_actual, SUM(monto_objetivo) as total_objetivo')->whereYear('created_at', now()->year)->first();
        $savingsProgress = $savingsData->total_objetivo > 0 ? round(($savingsData->total_actual / $savingsData->total_objetivo) * 100, 1) : 0;
        $savingsRate = $savingsProgress;
        $expenseRatio = $totalAsignado > 0 ? round(($totalGastado / $totalAsignado) * 100, 1) : 0;
        $monthlyTrend = $prevMonthSpent > 0 ? round((($currentMonthSpent - $prevMonthSpent) / $prevMonthSpent) * 100, 1) : 0;

        // Calculate real saldo disponible from movimientos (mes actual)
        $movData = Movimiento::selectRaw("SUM(CASE WHEN tipo = 'ingreso' THEN monto ELSE 0 END) as total_ingresos, SUM(CASE WHEN tipo = 'gasto' THEN monto ELSE 0 END) as total_gastos")->whereMonth('fecha', now()->month)->whereYear('fecha', now()->year)->first();
        $aportesMes = Ahorro::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('monto_ahorrado');
        $saldoReal = ($movData->total_ingresos ?? 0) - (($movData->total_gastos ?? 0) + ($aportesMes ?? 0));
        $presupuestoRestante = $totalAsignado - $totalGastado;
        $diferenciaPresupuestoSaldo = $saldoReal - $presupuestoRestante;

        return [
            Stat::make('Presupuestos Activos', Presupuesto::active()->count())
                ->description('Número total de presupuestos activos')
                ->descriptionIcon('heroicon-m-chart-pie')
                ->color('success'),

            Stat::make('Total Movimientos', Movimiento::count())
                ->description('Número total de movimientos registrados')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),

            Stat::make('Tasa de Ahorro', $savingsRate . '%')
                ->description('Porcentaje de metas de ahorro completadas')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color($savingsRate >= 80 ? 'success' : ($savingsRate >= 50 ? 'warning' : 'danger')),

            Stat::make('Ratio de Gastos', $expenseRatio . '%')
                ->description('Porcentaje del presupuesto utilizado')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color($expenseRatio <= 80 ? 'success' : ($expenseRatio <= 95 ? 'warning' : 'danger')),

            Stat::make('Tendencia Mensual', ($monthlyTrend >= 0 ? '+' : '') . $monthlyTrend . '%')
                ->description('Cambio en gastos vs mes anterior')
                ->descriptionIcon($monthlyTrend <= 0 ? 'heroicon-o-arrow-trending-down' : 'heroicon-o-arrow-trending-up')
                ->color($monthlyTrend <= 0 ? 'success' : 'danger'),

            Stat::make('Informe de Presupuesto', $diferenciaPresupuestoSaldo >= 0 ? 'Te Sobran ' . number_format($diferenciaPresupuestoSaldo, 2) : 'Te Faltan ' . number_format(abs($diferenciaPresupuestoSaldo), 2))
                ->description($diferenciaPresupuestoSaldo >= 0 ? 'Presupuesto cubierto por saldo' : ' El Presupuesto supera el saldo disponible')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color($diferenciaPresupuestoSaldo >= 0 ? 'success' : 'danger')
                ->extraAttributes($diferenciaPresupuestoSaldo < 0 ? ['class' => 'ring-4 ring-red-500/20 bg-red-50 dark:bg-red-500/10 dark:ring-red-400/30 shadow-lg'] : ['class' => 'ring-2 ring-green-400/30 bg-green-50/50 dark:bg-green-900/20 shadow-md'] ),
 
            Stat::make('Saldo Disponible', 'Tienes ' . number_format($saldoReal, 2))
                ->description('De dinero disponible actualmente')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($saldoReal >= 0 ? 'success' : 'danger'),
        ];
    }
}
