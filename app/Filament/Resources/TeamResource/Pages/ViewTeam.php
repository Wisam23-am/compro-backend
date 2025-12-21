<?php

namespace App\Filament\Resources\TeamResource\Pages;

use App\Filament\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;

class ViewTeam extends ViewRecord
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Configure the infolist for viewing team member details.
     */
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Member Profile')
                    ->schema([
                        Infolists\Components\ImageEntry::make('image')
                            ->label('Profile Photo')
                            ->height(200)
                            ->defaultImageUrl(url('/images/placeholder-avatar.png')),

                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Full Name')
                                    ->weight(FontWeight::Bold)
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large),

                                Infolists\Components\TextEntry::make('position')
                                    ->label('Position')
                                    ->icon('heroicon-m-briefcase'),

                                Infolists\Components\TextEntry::make('location')
                                    ->label('Location')
                                    ->icon('heroicon-m-map-pin')
                                    ->placeholder('Not specified'),

                                Infolists\Components\IconEntry::make('is_active')
                                    ->label('Status')
                                    ->boolean()
                                    ->trueIcon('heroicon-o-check-circle')
                                    ->falseIcon('heroicon-o-x-circle')
                                    ->trueColor('success')
                                    ->falseColor('danger'),
                            ]),
                    ])
                    ->columns(1),

                Infolists\Components\Section::make('Additional Information')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('sort_order')
                                    ->label('Display Order')
                                    ->badge()
                                    ->color('gray'),

                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Added On')
                                    ->dateTime('F j, Y'),

                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label('Last Updated')
                                    ->dateTime('F j, Y \a\t H:i'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    /**
     * Get the heading for the page.
     */
    public function getHeading(): string
    {
        return $this->record->name;
    }

    /**
     * Get the subheading for the page.
     */
    public function getSubheading(): ?string
    {
        return $this->record->position;
    }
}
