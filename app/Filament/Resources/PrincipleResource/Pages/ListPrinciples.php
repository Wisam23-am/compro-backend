<?php

namespace App\Filament\Resources\PrincipleResource\Pages;

use App\Filament\Resources\PrincipleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

/**
 * List Principles Page
 * 
 * Displays a table of all principles with sorting and filtering capabilities.
 */
class ListPrinciples extends ListRecords
{
    protected static string $resource = PrincipleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Principle created')
                        ->body('The principle has been created successfully.')
                ),
        ];
    }
}
