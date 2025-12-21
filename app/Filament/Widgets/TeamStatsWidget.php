<?php

namespace App\Filament\Widgets;

use App\Models\Team;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Team Stats Widget
 * 
 * Displays statistics about team members including active/inactive counts
 * and recent additions.
 */
class TeamStatsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $totalMembers = Team::count();
        $activeMembers = Team::active()->count();
        $inactiveMembers = $totalMembers - $activeMembers;
        $recentlyAdded = Team::where('created_at', '>=', now()->subDays(30))->count();

        return [
            Stat::make('Total Team Members', $totalMembers)
                ->description('All registered members')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->chart($this->getTeamGrowthChart()),

            Stat::make('Active Members', $activeMembers)
                ->description('Currently active on website')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Inactive Members', $inactiveMembers)
                ->description('Hidden from public view')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color($inactiveMembers > 0 ? 'warning' : 'gray'),

            Stat::make('Recently Added', $recentlyAdded)
                ->description('Added in the last 30 days')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
        ];
    }

    /**
     * Get team growth chart data for the last 7 days.
     */
    protected function getTeamGrowthChart(): array
    {
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $count = Team::where('created_at', '<=', $date->endOfDay())->count();
            $data[] = $count;
        }

        return $data;
    }
}
