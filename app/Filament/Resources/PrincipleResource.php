<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrincipleResource\Pages;
use App\Filament\Resources\PrincipleResource\RelationManagers;
use App\Models\Principle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;

/**
 * Principle Resource
 * 
 * Comprehensive Filament resource for managing company principles.
 * Includes advanced forms, sortable tables, bulk actions, and custom actions.
 */
class PrincipleResource extends Resource
{
    protected static ?string $model = Principle::class;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    protected static ?string $navigationLabel = 'Principles';

    protected static ?string $modelLabel = 'Principle';

    protected static ?string $pluralModelLabel = 'Principles';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    /**
     * Get the navigation badge for the resource.
     * Shows the count of active principles.
     */
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::active()->count();
    }

    /**
     * Define the form schema for creating and editing principles.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Main Information Section
                Section::make('Basic Information')
                    ->description('Enter the core details of the principle.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->label('Title')
                                    ->placeholder('e.g., Innovation First')
                                    ->helperText('A unique, concise title for the principle.')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('subtitle')
                                    ->maxLength(255)
                                    ->label('Subtitle')
                                    ->placeholder('e.g., Leading with creativity')
                                    ->helperText('Optional subtitle for additional context.')
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(4)
                            ->maxLength(1000)
                            ->label('Description')
                            ->placeholder('Describe this principle in detail...')
                            ->helperText('Provide a comprehensive description of what this principle means.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // Media Section
                Section::make('Media Assets')
                    ->description('Upload visual elements for the principle.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->label('Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('principles/images')
                                    ->visibility('public')
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                    ])
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('16:9')
                                    ->imageResizeTargetWidth('1200')
                                    ->imageResizeTargetHeight('675')
                                    ->maxSize(2048)
                                    ->helperText('Upload an image (max 2MB). Recommended: 1200x675px (16:9 ratio).')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('icon')
                                    ->label('Icon Name')
                                    ->placeholder('e.g., shield, users, leaf')
                                    ->helperText('Enter a Heroicon name (lowercase) or upload an SVG file below.')
                                    ->maxLength(255)
                                    ->columnSpan(1),

                                Forms\Components\FileUpload::make('icon_file')
                                    ->label('Icon File (SVG) - Optional')
                                    ->disk('public')
                                    ->directory('principles/icons')
                                    ->visibility('public')
                                    ->acceptedFileTypes(['image/svg+xml'])
                                    ->maxSize(512)
                                    ->helperText('Or upload a custom SVG icon (max 512KB).')
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // Settings Section
                Section::make('Settings')
                    ->description('Configure display and ordering options.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->label('Sort Order')
                                    ->helperText('Controls the display order (lower numbers appear first).')
                                    ->columnSpan(1),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Active Status')
                                    ->default(true)
                                    ->inline(false)
                                    ->helperText('Toggle to show/hide this principle on the website.')
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    /**
     * Define the table schema for listing principles.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->alignCenter()
                    ->width('50px'),

                Tables\Columns\TextColumn::make('icon')
                    ->label('Icon')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn($state) => $state ?: 'No Icon')
                    ->description(fn($state) => $state ? 'heroicon-o-' . strtolower($state) : null),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->width('80px')
                    ->height('60px'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(30),

                Tables\Columns\TextColumn::make('subtitle')
                    ->searchable()
                    ->limit(40)
                    ->toggleable()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->wrap(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable()
                    ->alignCenter(),

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
                    ->placeholder('All')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only'),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),

                    Tables\Actions\EditAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Principle updated')
                                ->body('The principle has been updated successfully.')
                        ),

                    Tables\Actions\Action::make('toggle_status')
                        ->label(fn(Principle $record) => $record->is_active ? 'Deactivate' : 'Activate')
                        ->icon(fn(Principle $record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn(Principle $record) => $record->is_active ? 'warning' : 'success')
                        ->requiresConfirmation()
                        ->modalHeading(fn(Principle $record) => $record->is_active ? 'Deactivate Principle' : 'Activate Principle')
                        ->modalDescription(fn(Principle $record) => $record->is_active
                            ? 'Are you sure you want to deactivate this principle? It will no longer be visible on the website.'
                            : 'Are you sure you want to activate this principle? It will become visible on the website.')
                        ->modalSubmitActionLabel(fn(Principle $record) => $record->is_active ? 'Deactivate' : 'Activate')
                        ->action(function (Principle $record) {
                            $record->is_active = !$record->is_active;
                            $record->save();

                            Notification::make()
                                ->success()
                                ->title('Status updated')
                                ->body("Principle {$record->title} has been " . ($record->is_active ? 'activated' : 'deactivated') . '.')
                                ->send();
                        }),

                    Tables\Actions\DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Principle deleted')
                                ->body('The principle has been moved to trash.')
                        ),

                    Tables\Actions\RestoreAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Principle restored')
                                ->body('The principle has been restored successfully.')
                        ),

                    Tables\Actions\ForceDeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Principle permanently deleted')
                                ->body('The principle has been permanently deleted.')
                        ),
                ])->tooltip('Actions'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each(fn($record) => $record->update(['is_active' => true]));

                            Notification::make()
                                ->success()
                                ->title('Principles activated')
                                ->body(count($records) . ' principles have been activated.')
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each(fn($record) => $record->update(['is_active' => false]));

                            Notification::make()
                                ->success()
                                ->title('Principles deactivated')
                                ->body(count($records) . ' principles have been deactivated.')
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Principles deleted')
                                ->body('Selected principles have been moved to trash.')
                        ),

                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Principles restored')
                                ->body('Selected principles have been restored.')
                        ),

                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Principles permanently deleted')
                                ->body('Selected principles have been permanently deleted.')
                        ),
                ]),
            ])
            ->reorderable('sort_order')
            ->emptyStateHeading('No principles yet')
            ->emptyStateDescription('Create your first principle to get started.')
            ->emptyStateIcon('heroicon-o-light-bulb')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus'),
            ]);
    }

    /**
     * Get the relations for the resource.
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Get the pages for the resource.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrinciples::route('/'),
            'create' => Pages\CreatePrinciple::route('/create'),
            'view' => Pages\ViewPrinciple::route('/{record}'),
            'edit' => Pages\EditPrinciple::route('/{record}/edit'),
        ];
    }

    /**
     * Get the eloquent query for the resource.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
