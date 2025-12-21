<?php

namespace App\Filament\Resources\PrincipleResource\Pages;

use App\Filament\Resources\PrincipleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

/**
 * Create Principle Page
 * 
 * Handles the creation of new principles with validation and notification.
 */
class CreatePrinciple extends CreateRecord
{
    protected static string $resource = PrincipleResource::class;

    /**
     * Get the redirect URL after creating a record.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Get the notification after creating a record.
     */
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Principle created successfully')
            ->body('The new principle has been added to the system.')
            ->duration(5000)
            ->send();
    }

    /**
     * Mutate form data before creating the record.
     * Used for data sanitization and validation.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Sanitize title and description
        $data['title'] = strip_tags($data['title']);
        $data['description'] = strip_tags($data['description']);

        if (isset($data['subtitle'])) {
            $data['subtitle'] = strip_tags($data['subtitle']);
        }

        return $data;
    }
}
