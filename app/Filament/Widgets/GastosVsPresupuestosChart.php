<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Presupuesto;
use Illuminate\Support\Facades\DB;


class GastosVsPresupuestosChart extends ChartWidget
{
    protected static ?string $heading = 'Gastos vs Presupuestos Totales';

    protected function getData(): array
    {
        $totalAsignado = Presupuesto::sum('monto_asignado');
        $totalGastado = Presupuesto::sum('monto_gastado');
        $remaining = $totalAsignado - $totalGastado;

        return [
            'datasets' => [
                [
                    'data' => [$totalGastado, $remaining],
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(75, 192, 192)',
                    ],
                    'borderColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(75, 192, 192)',
                    ],
                ],
            ],
            'labels' => ['Gastado', 'Restante'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
