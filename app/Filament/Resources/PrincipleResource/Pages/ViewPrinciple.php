<?php

namespace App\Filament\Resources\PrincipleResource\Pages;

use App\Filament\Resources\PrincipleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;

/**
 * View Principle Page
 * 
 * Displays detailed view of a single principle with action buttons.
 */
class ViewPrinciple extends ViewRecord
{
    protected static string $resource = PrincipleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->icon('heroicon-o-pencil'),

            Actions\Action::make('toggle_status')
                ->label(fn() => $this->record->is_active ? 'Deactivate' : 'Activate')
                ->icon(fn() => $this->record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                ->color(fn() => $this->record->is_active ? 'warning' : 'success')
                ->requiresConfirmation()
                ->modalHeading(fn() => $this->record->is_active ? 'Deactivate Principle' : 'Activate Principle')
                ->modalDescription(fn() => $this->record->is_active
                    ? 'Are you sure you want to deactivate this principle?'
                    : 'Are you sure you want to activate this principle?')
                ->action(function () {
                    $this->record->is_active = !$this->record->is_active;
                    $this->record->save();

                    Notification::make()
                        ->success()
                        ->title('Status updated')
                        ->body("Principle has been " . ($this->record->is_active ? 'activated' : 'deactivated') . '.')
                        ->send();
                }),

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
                        ->body('The principle has been permanently removed.')
                ),
        ];
    }
}
