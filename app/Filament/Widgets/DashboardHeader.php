<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Presupuesto;
use App\Models\Movimiento;
use Illuminate\Support\Facades\Auth;

class DashboardHeader extends Widget
{
    protected static string $view = 'filament.widgets.dashboard-header';

    protected int | string | array $columnSpan = 'full';

    public function getUserName(): string
    {
        return Auth::user()->name ?? 'Usuario';
    }

    public function getCurrentMonth(): string
    {
        return now()->locale('es')->monthName;
    }

    public function getTotalAssigned(): float
    {
        return Presupuesto::sum('monto_asignado');
    }

    public function getTotalSpent(): float
    {
        return Presupuesto::sum('monto_gastado');
    }

    public function getSavingsRate(): float
    {
        $assigned = $this->getTotalAssigned();
        $spent = $this->getTotalSpent();
        return $assigned > 0 ? round((($assigned - $spent) / $assigned) * 100, 1) : 0;
    }

    public function getTotalMovements(): int
    {
        return Movimiento::count();
    }
}
