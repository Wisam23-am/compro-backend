<?php

namespace App\Filament\Resources\PrincipleResource\Pages;

use App\Filament\Resources\PrincipleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

/**
 * Edit Principle Page
 * 
 * Handles editing existing principles with validation and notification.
 */
class EditPrinciple extends EditRecord
{
    protected static string $resource = PrincipleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),

            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Principle deleted')
                        ->body('The principle has been moved to trash.')
                ),

            Actions\RestoreAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Principle restored')
                        ->body('The principle has been restored successfully.')
                ),

            Actions\ForceDeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->warning()
                        ->title('Principle permanently deleted')
                        ->body('The principle has been permanently removed from the system.')
                ),
        ];
    }

    /**
     * Get the redirect URL after saving.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Get the notification after saving.
     */
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Principle updated successfully')
            ->body('Changes have been saved.')
            ->duration(5000)
            ->send();
    }

    /**
     * Mutate form data before saving.
     * Used for data sanitization.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Sanitize inputs to prevent XSS
        $data['title'] = strip_tags($data['title']);
        $data['description'] = strip_tags($data['description']);

        if (isset($data['subtitle'])) {
            $data['subtitle'] = strip_tags($data['subtitle']);
        }

        return $data;
    }
}
