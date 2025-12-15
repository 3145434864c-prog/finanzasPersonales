<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Movimiento;
use Illuminate\Support\Facades\DB;

class CashFlowTrendsChart extends ChartWidget
{
    protected int|string|array $columnSpan = 12;

    protected static ?string $heading = 'Tendencias de Flujo de Caja';

    public function getDescription(): ?string
    {
        return 'Tendencias de ingresos y gastos a lo largo del tiempo.';
    }

    protected function getData(): array
    {
        // Obtener datos mensuales de los últimos 12 meses
        $data = Movimiento::select(
            DB::raw('YEAR(fecha) as year'),
            DB::raw('MONTH(fecha) as month'),
            DB::raw('SUM(CASE WHEN tipo = "ingreso" THEN monto ELSE 0 END) as ingresos'),
            DB::raw('SUM(CASE WHEN tipo = "gasto" THEN monto ELSE 0 END) as gastos')
        )
        ->where('fecha', '>=', now()->subMonths(12))
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        $labels = [];
        $ingresos = [];
        $gastos = [];
        $neto = [];

        foreach ($data as $item) {
            $labels[] = $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            $ingresos[] = $item->ingresos;
            $gastos[] = $item->gastos;
            $neto[] = $item->ingresos - $item->gastos;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ingresos',
                    'data' => $ingresos,
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.3)',
                    'fill' => true,
                ],
                [
                    'label' => 'Gastos',
                    'data' => $gastos,
                    'borderColor' => 'rgb(239, 68, 68)',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.3)',
                    'fill' => true,
                ],
                [
                    'label' => 'Flujo Neto',
                    'data' => $neto,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => false,
                    'borderWidth' => 3,
                    'pointBackgroundColor' => 'rgb(59, 130, 246)',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 5,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
                'tooltip' => [
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'titleColor' => '#fff',
                    'bodyColor' => '#fff',
                    'callbacks' => [
                        'label' => 'function(context) {
                            let label = context.dataset.label || "";
                            if (label) {
                                label += ": S/ " + context.parsed.y.toFixed(2);
                            }
                            return label;
                        }',
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'display' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Mes',
                    ],
                ],
                'y' => [
                    'display' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Monto (S/)',
                    ],
                    'ticks' => [
                        'callback' => 'function(value) {
                            return "S/ " + value.toFixed(2);
                        }',
                    ],
                ],
            ],
        ];
    }
}
