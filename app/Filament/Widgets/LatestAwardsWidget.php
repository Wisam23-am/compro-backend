<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AwardResource;
use App\Models\Award;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

/**
 * Latest Awards Widget
 * 
 * Displays the most recently created awards on the dashboard.
 */
class LatestAwardsWidget extends BaseWidget
{
    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Latest Awards';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Award::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(40)
                    ->url(fn(Award $record): string => AwardResource::getUrl('edit', ['record' => $record])),

                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('featured')
                    ->boolean()
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->label('Featured'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->label('Status'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->label('Created'),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->url(fn(Award $record): string => AwardResource::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-o-pencil')
                    ->tooltip('Edit'),
            ]);
    }
}
