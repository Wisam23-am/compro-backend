<?php

namespace App\Filament\Resources\TeamResource\Pages;

use App\Filament\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateTeam extends CreateRecord
{
    protected static string $resource = TeamResource::class;

    /**
     * Get the redirect URL after creation.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Handle the record creation.
     */
    protected function afterCreate(): void
    {
        // Auto-assign sort_order if not specified
        if ($this->record->sort_order === 0) {
            $maxOrder = $this->record->newQuery()->max('sort_order') ?? 0;
            $this->record->update(['sort_order' => $maxOrder + 1]);
        }

        Notification::make()
            ->success()
            ->title('Team member added')
            ->body("Successfully added {$this->record->name} to the team.")
            ->send();
    }

    /**
     * Get the heading for the page.
     */
    public function getHeading(): string
    {
        return 'Add New Team Member';
    }
}
