<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\DashboardHeader;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\FinancialStatusCard;
use App\Filament\Widgets\GastosVsPresupuestosChart;
use App\Filament\Widgets\CashFlowTrendsChart;
use App\Filament\Widgets\TopSpendingCategoriesChart;
use App\Filament\Widgets\PresupuestoChart;
use App\Filament\Widgets\RecentMovimientos;
use App\Filament\Widgets\BudgetAlerts;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            FinancialStatusCard::class,
            StatsOverview::class,
            BudgetAlerts::class,
            RecentMovimientos::class,
            GastosVsPresupuestosChart::class,
            TopSpendingCategoriesChart::class,
            CashFlowTrendsChart::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'md' => 2,
            'xl' => 4,
        ];
    }
}
