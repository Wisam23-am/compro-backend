<?php

namespace App\Filament\Widgets;

use App\Models\Team;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

/**
 * Latest Team Widget
 * 
 * Displays the most recently added team members on the dashboard with pagination.
 */
class LatestTeamWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Latest Team Members';

    protected static ?string $pollingInterval = '30s';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Team::query()->latest()
            )
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder-avatar.png'))
                    ->size(40),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(Team $record): string => $record->position ?? ''),

                Tables\Columns\TextColumn::make('location')
                    ->icon('heroicon-m-map-pin')
                    ->placeholder('Not specified')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->description(fn(Team $record): string => $record->created_at->diffForHumans()),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn(Team $record): string => route('filament.admin.resources.teams.view', ['record' => $record])),
                Tables\Actions\Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn(Team $record): string => route('filament.admin.resources.teams.edit', ['record' => $record])),
            ])
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([10, 25, 50, 100])
            ->emptyStateHeading('No team members yet')
            ->emptyStateDescription('Add your first team member to get started.')
            ->emptyStateIcon('heroicon-o-user-group');
    }
}
