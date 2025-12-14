<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Presupuesto;

class FinancialStatusCard extends BaseWidget
{
    protected function getStats(): array
    {
        $asignado = Presupuesto::sum('monto_asignado');
        $gastado = Presupuesto::sum('monto_gastado');
        $diferencia = $asignado - $gastado;

        if ($diferencia < 0) {
            // Exceeded budget - impactful alert
            $color = 'danger';
            $icon = 'heroicon-m-exclamation-triangle';
            $description = '¡ALERTA! Has excedido tu presupuesto';
        } elseif ($gastado > $asignado * 0.8) {
            // Close to budget - warning
            $color = 'warning';
            $icon = 'heroicon-m-exclamation-circle';
            $description = 'Cuidado: Estás cerca de tu límite presupuestario';
        } else {
            // Good - positive message
            $color = 'success';
            $icon = 'heroicon-m-check-circle';
            $description = '¡Excelente! Vas bien con tu presupuesto';
        }

        return [
            Stat::make('Saldo Presupuestario', 'S/ ' . number_format(abs($diferencia), 2))
                ->description($description)
                ->descriptionIcon($icon)
                ->color($color),
        ];
    }
}
