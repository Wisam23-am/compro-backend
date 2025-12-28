<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AwardResource\Pages;
use App\Models\Award;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

/**
 * Award Resource
 * 
 * Manages company awards and achievements with full CRUD operations,
 * drag-and-drop reordering, and status management.
 */
class AwardResource extends Resource
{
    protected static ?string $model = Award::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'title';

    /**
     * Get the navigation badge (total awards count).
     */
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    /**
     * Get navigation badge color.
     */
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'success' : 'primary';
    }

    /**
     * Define the form schema.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Award Information')
                    ->description('Basic details about the award')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('e.g., Innovation Excellence Award')
                            ->helperText('Enter a unique title for the award'),

                        Forms\Components\TextInput::make('location')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Bali, 2020')
                            ->helperText('Location and year of the award'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('Display Settings')
                    ->description('Control how this award appears on the website')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active Status')
                            ->helperText('Only active awards will be visible on the website')
                            ->default(true)
                            ->inline(false),

                        Forms\Components\Toggle::make('featured')
                            ->label('Featured Award')
                            ->helperText('Featured awards are highlighted on the website')
                            ->default(false)
                            ->inline(false),

                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(fn() => Award::getNextSortOrder())
                            ->required()
                            ->minValue(0)
                            ->helperText('Lower numbers appear first. Use drag-and-drop in the table for easier reordering.'),
                    ])
                    ->columns(3)
                    ->collapsible(),
            ]);
    }

    /**
     * Define the table schema.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->searchable()
                    ->width('80px'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),

                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('featured')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->label('Featured')
                    ->tooltip(fn($record) => $record->featured ? 'Featured Award' : 'Not Featured'),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active')
                    ->onColor('success')
                    ->offColor('danger')
                    ->afterStateUpdated(function ($record, $state) {
                        Notification::make()
                            ->title('Status Updated')
                            ->body("Award \"{$record->title}\" is now " . ($state ? 'active' : 'inactive'))
                            ->success()
                            ->send();
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All awards')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),

                Tables\Filters\TernaryFilter::make('featured')
                    ->label('Featured Status')
                    ->placeholder('All awards')
                    ->trueLabel('Featured only')
                    ->falseLabel('Not featured'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),

                    Tables\Actions\EditAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Award updated')
                                ->body('The award has been updated successfully.')
                        ),

                    Tables\Actions\Action::make('toggleFeatured')
                        ->label(fn($record) => $record->featured ? 'Unfeature' : 'Feature')
                        ->icon(fn($record) => $record->featured ? 'heroicon-o-star' : 'heroicon-s-star')
                        ->color(fn($record) => $record->featured ? 'gray' : 'warning')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->update(['featured' => !$record->featured]);
                            Notification::make()
                                ->title($record->featured ? 'Award Featured' : 'Award Unfeatured')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Award deleted')
                                ->body('The award has been deleted successfully.')
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->update(['is_active' => true]);
                            Notification::make()
                                ->title('Awards Activated')
                                ->body(count($records) . ' award(s) have been activated.')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->update(['is_active' => false]);
                            Notification::make()
                                ->title('Awards Deactivated')
                                ->body(count($records) . ' award(s) have been deactivated.')
                                ->warning()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Awards deleted')
                                ->body('Selected awards have been deleted successfully.')
                        ),
                ]),
            ])
            ->reorderable('sort_order')
            ->emptyStateHeading('No awards yet')
            ->emptyStateDescription('Create your first award to get started.')
            ->emptyStateIcon('heroicon-o-trophy')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create First Award')
                    ->icon('heroicon-o-plus'),
            ]);
    }

    /**
     * Get the pages available for the resource.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAwards::route('/'),
            'create' => Pages\CreateAward::route('/create'),
            'view' => Pages\ViewAward::route('/{record}'),
            'edit' => Pages\EditAward::route('/{record}/edit'),
        ];
    }

    /**
     * Get the eloquent query for the resource.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes();
    }
}
