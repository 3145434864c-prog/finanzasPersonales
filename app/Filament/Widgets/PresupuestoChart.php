<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Presupuesto;
use Illuminate\Support\Facades\DB;

class PresupuestoChart extends ChartWidget
{
    protected static ?string $heading = 'Presupuestos por Mes';

    protected function getData(): array
    {
        $data = Presupuesto::select(
            DB::raw('mes as month'),
            DB::raw('SUM(monto_asignado) as total_asignado'),
            DB::raw('SUM(monto_gastado) as total_gastado')
        )
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Monto Asignado',
                    'data' => $data->pluck('total_asignado')->toArray(),
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                ],
                [
                    'label' => 'Monto Gastado',
                    'data' => $data->pluck('total_gastado')->toArray(),
                    'borderColor' => 'rgb(255, 99, 132)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                ],
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
