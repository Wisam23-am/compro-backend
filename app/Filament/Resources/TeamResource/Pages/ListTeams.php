<?php

namespace App\Filament\Resources\TeamResource\Pages;

use App\Filament\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListTeams extends ListRecords
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Team member created')
                        ->body('New team member has been added successfully.')
                ),
        ];
    }

    /**
     * Get the heading for the page.
     */
    public function getHeading(): string
    {
        return 'Team Members';
    }

    /**
     * Get the subheading for the page.
     */
    public function getSubheading(): ?string
    {
        $activeCount = $this->getModel()::active()->count();
        $totalCount = $this->getModel()::count();

        return "{$activeCount} active out of {$totalCount} total members";
    }
}
