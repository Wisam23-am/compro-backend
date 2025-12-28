<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    /**
     * Get the widgets that should be displayed on the dashboard.
     * 
     * Order: Statistics followed immediately by their latest table widget
     */
    public function getWidgets(): array
    {
        return [
            // Principle Module
            \App\Filament\Widgets\PrincipleStatsWidget::class,
            \App\Filament\Widgets\LatestPrinciplesWidget::class,

            // Team Module
            \App\Filament\Widgets\TeamStatsWidget::class,
            \App\Filament\Widgets\LatestTeamWidget::class,

            // Award Module
            \App\Filament\Widgets\AwardStatsWidget::class,
            \App\Filament\Widgets\LatestAwardsWidget::class,
        ];
    }

    /**
     * Get the columns configuration for the widgets.
     * Full width for better visibility
     */
    public function getColumns(): int|string|array
    {
        return 1;
    }
}
