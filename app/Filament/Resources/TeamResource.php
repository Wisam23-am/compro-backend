<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Team Resource
 * 
 * Comprehensive Filament resource for managing team members.
 * Includes advanced forms, sortable tables, bulk actions, and status management.
 */
class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Team';

    protected static ?string $modelLabel = 'Team Member';

    protected static ?string $pluralModelLabel = 'Team Members';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    /**
     * Get the navigation badge for the resource.
     * Shows the count of active team members.
     */
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::active()->count();
    }

    /**
     * Get the navigation badge color.
     */
    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }

    /**
     * Define the form schema for creating and editing team members.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Basic Information Section
                Section::make('Member Information')
                    ->description('Enter the core details of the team member.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->label('Full Name')
                                    ->placeholder('e.g., John Doe')
                                    ->helperText('Enter the team member\'s full name.')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('position')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Position')
                                    ->placeholder('e.g., Senior Developer')
                                    ->helperText('Job title or role in the company.')
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\TextInput::make('location')
                            ->maxLength(255)
                            ->label('Location')
                            ->placeholder('e.g., New York, USA')
                            ->helperText('City or region where the member is based.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // Media Section
                Section::make('Profile Image')
                    ->description('Upload a profile photo for the team member.')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Profile Photo')
                            ->image()
                            ->disk('public')
                            ->directory('team-members')
                            ->visibility('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                                '4:3',
                            ])
                            ->maxSize(5120)
                            ->helperText('Upload a profile image (max 5MB). Square or 4:3 aspect ratio recommended.')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                // Status and Ordering Section
                Section::make('Status & Display')
                    ->description('Configure visibility and display order.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Active Status')
                                    ->helperText('Toggle to show/hide this member on the website.')
                                    ->default(true)
                                    ->inline(false)
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->label('Sort Order')
                                    ->helperText('Lower numbers appear first. Use 0 for automatic ordering.')
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    /**
     * Define the table schema for listing team members.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder-avatar.png'))
                    ->size(50),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(Team $record): string => $record->position ?? ''),

                Tables\Columns\TextColumn::make('position')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->icon('heroicon-m-map-pin')
                    ->placeholder('Not specified'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->numeric()
                    ->alignCenter()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All members')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->queries(
                        true: fn(Builder $query) => $query->where('is_active', true),
                        false: fn(Builder $query) => $query->where('is_active', false),
                    ),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Team member updated')
                                ->body('The team member has been updated successfully.')
                        ),
                    Tables\Actions\DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Team member deleted')
                                ->body('The team member has been removed from the system.')
                        ),
                    Tables\Actions\Action::make('toggle_status')
                        ->label(fn(Team $record) => $record->is_active ? 'Deactivate' : 'Activate')
                        ->icon(fn(Team $record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn(Team $record) => $record->is_active ? 'warning' : 'success')
                        ->requiresConfirmation()
                        ->action(function (Team $record) {
                            $record->update(['is_active' => !$record->is_active]);

                            Notification::make()
                                ->success()
                                ->title('Status updated')
                                ->body("Team member is now " . ($record->is_active ? 'active' : 'inactive') . ".")
                                ->send();
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Team members deleted')
                                ->body('Selected team members have been removed.')
                        ),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each->update(['is_active' => true]);

                            Notification::make()
                                ->success()
                                ->title('Members activated')
                                ->body($records->count() . ' team members have been activated.')
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each->update(['is_active' => false]);

                            Notification::make()
                                ->success()
                                ->title('Members deactivated')
                                ->body($records->count() . ' team members have been deactivated.')
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->reorderable('sort_order')
            ->emptyStateHeading('No team members yet')
            ->emptyStateDescription('Get started by adding your first team member.')
            ->emptyStateIcon('heroicon-o-user-group')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Team Member')
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
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'view' => Pages\ViewTeam::route('/{record}'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
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
