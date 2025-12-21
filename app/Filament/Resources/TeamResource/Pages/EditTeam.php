<?php

namespace App\Filament\Resources\TeamResource\Pages;

use App\Filament\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditTeam extends EditRecord
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Team member deleted')
                        ->body('The team member has been removed successfully.')
                ),
        ];
    }

    /**
     * Get the redirect URL after update.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Handle after record update.
     */
    protected function afterSave(): void
    {
        Notification::make()
            ->success()
            ->title('Changes saved')
            ->body("Successfully updated {$this->record->name}'s information.")
            ->send();
    }

    /**
     * Get the heading for the page.
     */
    public function getHeading(): string
    {
        return 'Edit Team Member';
    }
}
