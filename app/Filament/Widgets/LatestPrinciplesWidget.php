<?php

namespace App\Filament\Widgets;

use App\Models\Principle;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

/**
 * Latest Principles Widget
 * 
 * Displays the most recently created or updated principles in a table format.
 * Provides quick access to recent principles from the dashboard.
 */
class LatestPrinciplesWidget extends BaseWidget
{
    /**
     * Widget heading.
     */
    protected static ?string $heading = 'Latest Principles';

    /**
     * Widget sort order on dashboard.
     */
    protected static ?int $sort = 2;

    /**
     * Widget column span (full width).
     */
    protected int|string|array $columnSpan = 'full';

    /**
     * Number of records to display.
     */
    protected int|string|array $defaultTableRecordsPerPage = 5;

    /**
     * Widget polling interval (disabled for better performance).
     */
    protected static ?string $pollingInterval = null;

    /**
     * Define the table schema.
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Principle::query()
                    ->latest('updated_at')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('icon')
                    ->label('Icon')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn($state) => $state ? ucfirst(strtolower($state)) : 'None'),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->width('60px')
                    ->height('45px'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(30)
                    ->url(fn(Principle $record): string => route('filament.admin.resources.principles.edit', ['record' => $record]))
                    ->color('primary'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->since()
                    ->description(fn(Principle $record): string => $record->updated_at->format('M d, Y H:i'))
                    ->color('gray'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn(Principle $record): string => route('filament.admin.resources.principles.view', ['record' => $record]))
                    ->openUrlInNewTab(false),

                Tables\Actions\Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn(Principle $record): string => route('filament.admin.resources.principles.edit', ['record' => $record]))
                    ->color('primary')
                    ->openUrlInNewTab(false),
            ])
            ->defaultSort('updated_at', 'desc')
            ->paginated(false)
            ->emptyStateHeading('No principles yet')
            ->emptyStateDescription('Create your first principle to see it here.')
            ->emptyStateIcon('heroicon-o-light-bulb');
    }

    /**
     * Get the table query.
     */
    protected function getTableQuery(): Builder
    {
        return Principle::query()->latest('updated_at');
    }

    /**
     * Check if the widget can be viewed.
     */
    public static function canView(): bool
    {
        return true;
    }
}
