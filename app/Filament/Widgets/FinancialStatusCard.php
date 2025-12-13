<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Presupuesto;

class FinancialStatusCard extends Widget
{
    protected static string $view = 'filament.widgets.financial-status-card';

    protected int | string | array $columnSpan = 'full';

    public function getTotalAsignado(): float
    {
        return Presupuesto::sum('monto_asignado');
    }

    public function getTotalGastado(): float
    {
        return Presupuesto::sum('monto_gastado');
    }

    public function getStatusMessage(): string
    {
        $asignado = $this->getTotalAsignado();
        $gastado = $this->getTotalGastado();

        if ($gastado > $asignado) {
            return '¡Alerta! Estás superando el presupuesto. Revisa tus gastos inmediatamente.';
        } else {
            return '¡Felicitaciones! Estás cumpliendo con tu presupuesto. ¡Sigue así!';
        }
    }

    public function getStatusColor(): string
    {
        $asignado = $this->getTotalAsignado();
        $gastado = $this->getTotalGastado();

        if ($gastado >= $asignado) {
            return 'danger';
        } else {
            return 'success';
        }
    }
}
