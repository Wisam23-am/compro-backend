<?php

namespace App\Filament\Widgets;

use App\Models\Award;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Award Statistics Widget
 * 
 * Displays key statistics about awards on the dashboard.
 */
class AwardStatsWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $totalAwards = Award::count();
        $activeAwards = Award::active()->count();
        $featuredAwards = Award::featured()->count();
        $inactiveAwards = $totalAwards - $activeAwards;

        return [
            Stat::make('Total Awards', $totalAwards)
                ->description('All awards in the system')
                ->descriptionIcon('heroicon-o-trophy')
                ->color('primary')
                ->chart([7, 12, 15, 18, 20, 22, $totalAwards]),

            Stat::make('Active Awards', $activeAwards)
                ->description('Currently visible on website')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart([5, 8, 10, 12, 15, 16, $activeAwards]),

            Stat::make('Featured Awards', $featuredAwards)
                ->description('Highlighted on homepage')
                ->descriptionIcon('heroicon-o-star')
                ->color('warning')
                ->chart([1, 2, 2, 3, 3, 4, $featuredAwards]),
        ];
    }
}
