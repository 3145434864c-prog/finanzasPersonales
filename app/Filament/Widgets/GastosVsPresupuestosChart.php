<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Presupuesto;
use Illuminate\Support\Facades\DB;


class GastosVsPresupuestosChart extends ChartWidget
{
    protected int|string|array $columnSpan = 4;

    protected static ?string $heading = 'Gastos vs Presupuestos Totales';

    public function getDescription(): ?string
    {
        $totalAsignado = Presupuesto::sum('monto_asignado');
        $totalGastado = Presupuesto::sum('monto_gastado');
        $porcentajeGastado = $totalAsignado > 0 ? round(($totalGastado / $totalAsignado) * 100, 1) : 0;

        return "Has utilizado {$porcentajeGastado}% de tu presupuesto total. " .
               ($porcentajeGastado > 90 ? '¡Considera revisar tus gastos!' :
                ($porcentajeGastado > 75 ? 'Estás cerca del límite.' : '¡Buen control de gastos!'));
    }

    protected function getData(): array
    {
        $totalAsignado = Presupuesto::sum('monto_asignado');
        $totalGastado = Presupuesto::sum('monto_gastado');
        $remaining = max(0, $totalAsignado - $totalGastado);

        return [
            'datasets' => [
                [
                    'data' => [$totalGastado, $remaining],
                    'backgroundColor' => [
                        'rgba(239, 68, 68, 0.8)', // Red-500 for spent
                        'rgba(34, 197, 94, 0.8)', // Green-500 for remaining
                    ],
                    'borderColor' => [
                        'rgb(239, 68, 68)',
                        'rgb(34, 197, 94)',
                    ],
                    'borderWidth' => 2,
                    'hoverBackgroundColor' => [
                        'rgba(239, 68, 68, 1)',
                        'rgba(34, 197, 94, 1)',
                    ],
                ],
            ],
            'labels' => [
                'Gastado (S/ ' . number_format($totalGastado, 2) . ')',
                'Restante (S/ ' . number_format($remaining, 2) . ')'
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 20,
                        'usePointStyle' => true,
                    ],
                ],
                'tooltip' => [
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'titleColor' => '#fff',
                    'bodyColor' => '#fff',
                    'callbacks' => [
                        'label' => 'function(context) {
                            let label = context.label || "";
                            let value = context.parsed;
                            return label + ": S/ " + value.toFixed(2);
                        }',
                    ],
                ],
            ],
            'animation' => [
                'animateScale' => true,
                'animateRotate' => true,
            ],
        ];
    }
}
