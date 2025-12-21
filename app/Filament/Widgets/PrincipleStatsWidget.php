<?php

namespace App\Filament\Widgets;

use App\Models\Principle;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Principle Stats Widget
 * 
 * Displays statistical overview of principles on the dashboard.
 * Shows total, active, and inactive principle counts with visual indicators.
 */
class PrincipleStatsWidget extends BaseWidget
{
    /**
     * Widget sort order on dashboard.
     */
    protected static ?int $sort = 1;

    /**
     * Widget polling interval (disabled for better performance).
     */
    protected static ?string $pollingInterval = null;

    /**
     * Define the stats to display.
     *
     * @return array
     */
    protected function getStats(): array
    {
        $totalPrinciples = Principle::count();
        $activePrinciples = Principle::active()->count();
        $inactivePrinciples = Principle::inactive()->count();

        // Calculate percentage of active principles
        $activePercentage = $totalPrinciples > 0
            ? round(($activePrinciples / $totalPrinciples) * 100, 1)
            : 0;

        return [
            Stat::make('Total Principles', $totalPrinciples)
                ->description('All principles in the system')
                ->descriptionIcon('heroicon-o-light-bulb')
                ->color('primary')
                ->chart($this->getMonthlyCreationChart()),

            Stat::make('Active Principles', $activePrinciples)
                ->description("{$activePercentage}% of total")
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ])
                ->url(route('filament.admin.resources.principles.index', [
                    'tableFilters' => ['is_active' => ['value' => true]],
                ])),

            Stat::make('Inactive Principles', $inactivePrinciples)
                ->description('Not visible on website')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('warning')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ])
                ->url(route('filament.admin.resources.principles.index', [
                    'tableFilters' => ['is_active' => ['value' => false]],
                ])),
        ];
    }

    /**
     * Get monthly creation chart data.
     * Shows the number of principles created in the last 7 months.
     *
     * @return array
     */
    protected function getMonthlyCreationChart(): array
    {
        $months = [];
        $data = [];

        // Get data for the last 7 months
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');

            $count = Principle::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $data[] = $count;
        }

        return $data;
    }

    /**
     * Check if the widget can be viewed.
     *
     * @return bool
     */
    public static function canView(): bool
    {
        return true;
    }
}
