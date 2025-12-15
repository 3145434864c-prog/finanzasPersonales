<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Movimiento;
use App\Models\Categoria;
use App\Models\Presupuesto;
use Illuminate\Support\Facades\DB;

class TopSpendingCategoriesChart extends ChartWidget
{
    protected int|string|array $columnSpan = 4;

    protected static ?string $heading = 'Principales Categorías de Gastos';

    public function getDescription(): ?string
    {
        $totalAsignado = Presupuesto::sum('monto_asignado');
        $topCategories = Movimiento::select('categoria_id', DB::raw('SUM(monto) as total_gastado'))
            ->where('tipo', 'gasto')
            ->groupBy('categoria_id')
            ->orderBy('total_gastado', 'desc')
            ->limit(3)
            ->get();

        $totalGastadoTop3 = $topCategories->sum('total_gastado');
        $porcentaje = $totalAsignado > 0 ? round(($totalGastadoTop3 / $totalAsignado) * 100, 1) : 0;

        return "Estas categorías representan el {$porcentaje}% del presupuesto total asignado. Te aconsejamos revisarlas para que manejes mejor tu economía.";
    }

    protected function getData(): array
    {
        // Obtener las 3 categorías con más gastos
        $topCategories = Movimiento::select('categoria_id', DB::raw('SUM(monto) as total_gastado'))
            ->where('tipo', 'gasto')
            ->groupBy('categoria_id')
            ->orderBy('total_gastado', 'desc')
            ->limit(3)
            ->with('categoria')
            ->get();

        $totalGastado = $topCategories->sum('total_gastado');

        $labels = [];
        $data = [];
        $backgroundColors = [
            'rgba(255, 99, 132, 0.8)',
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 205, 86, 0.8)',
        ];
        $borderColors = [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)',
        ];

        foreach ($topCategories as $index => $category) {
            $categoriaNombre = $category->categoria->nombre ?? 'Sin Categoría';
            $monto = $category->total_gastado;
            $porcentaje = $totalGastado > 0 ? round(($monto / $totalGastado) * 100, 1) : 0;
            $labels[] = "{$categoriaNombre} ({$porcentaje}%)";
            $data[] = $monto;
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y', // Para barras horizontales
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'enabled' => true,
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'titleColor' => '#fff',
                    'bodyColor' => '#fff',
                    'borderColor' => 'rgba(255, 255, 255, 0.2)',
                    'borderWidth' => 1,
                    'cornerRadius' => 8,
                    'callbacks' => [
                        'label' => 'function(context) {
                            let label = context.label || "";
                            if (label) {
                                label += ": S/ " + context.parsed.x.toFixed(2);
                            }
                            return label;
                        }',
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'color' => '#666',
                        'font' => [
                            'size' => 12,
                            'weight' => 'bold',
                        ],
                        'callback' => 'function(value) {
                            return "S/ " + value.toFixed(2);
                        }',
                    ],
                ],
                'y' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'color' => '#666',
                        'font' => [
                            'size' => 12,
                            'weight' => 'bold',
                        ],
                    ],
                ],
            ],
            'elements' => [
                'bar' => [
                    'borderRadius' => 8,
                    'borderSkipped' => false,
                ],
            ],
            'animation' => [
                'duration' => 1000,
                'easing' => 'easeOutQuart',
            ],
        ];
    }
}
