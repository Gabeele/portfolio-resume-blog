<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $slug = 'projects';

    protected static string|UnitEnum|null $navigationGroup = 'Portfolio';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                TextInput::make('role')
                    ->required()
                    ->maxLength(120),

                RichEditor::make('description')
                    ->toolbarButtons(['bold', 'italic', 'link', 'bulletList', 'orderedList', 'redo', 'undo'])
                    ->columnSpanFull()
                    ->required(),

                TextInput::make('url')
                    ->url()
                    ->maxLength(255),

                TextInput::make('repo')
                    ->label('Repository Link')
                    ->url()
                    ->maxLength(255),

                FileUpload::make('image')
                    ->label('Image')
                    ->columnSpanFull()
                    ->image()
                    ->imagePreviewHeight('250'),

                TextEntry::make('created_at')
                    ->label('Created Date')
                    ->hiddenOn('create')
                    ->state(fn(?Project $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                TextEntry::make('updated_at')
                    ->label('Last Modified Date')
                    ->hiddenOn('create')
                    ->state(fn(?Project $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Grid::make()
                    ->schema([
                        Split::make([
                            ImageColumn::make('image')
                                ->label('')
                                ->imageHeight('80')
                                ->imageWidth('80'),
                            Stack::make([
                                TextColumn::make('title')
                                    ->label('Title')
                                    ->weight('bold')
                                    ->limit(60),

                                TextColumn::make('role')
                                    ->label('Role')
                                    ->toggleable()
                                    ->extraAttributes(['class' => 'text-sm text-gray-500']),

                                TextColumn::make('description')
                                    ->label('Description')
                                    ->html()
                                    ->limit(50)
                                    ->wrap(),

                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        TextColumn::make('url')
                                            ->label('Live')
                                            ->formatStateUsing(fn($state, $record) => $state ? 'Project' : null)
                                            ->url(fn($record) => $record->url)
                                            ->openUrlInNewTab(),

                                        TextColumn::make('repo')
                                            ->label('Repo')
                                            ->formatStateUsing(fn($state, $record) => $state ? 'Repo' : null)
                                            ->url(fn($record) => $record->repo)
                                            ->openUrlInNewTab(),
                                    ]),
                            ])->grow(),
                        ]),
                    ])
                    ->columns(1),
            ])
            ->contentGrid([
                'md' => 1,
                'xl' => 2,
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(fn(Table $table): int => 10);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['user']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'user.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->user) {
            $details['User'] = $record->user->name;
        }

        return $details;
    }
}
