<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkExperienceResource\Pages;
use App\Models\WorkExperience;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class WorkExperienceResource extends Resource
{
    protected static ?string $model = WorkExperience::class;
    protected static ?string $slug = 'work-experiences';
    protected static string|UnitEnum|null $navigationGroup = 'Repository';
    protected static ?int $navigationSort = 4;
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('business')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(1),

                TextInput::make('location')
                    ->maxLength(255)
                    ->datalist([
                        'Remote', 'Toronto, ON', 'Waterloo, ON',
                    ])
                    ->required()
                    ->columnSpan(1),


                TextInput::make('role')
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->required(),

                RichEditor::make('description')
                    ->toolbarButtons(['bold', 'italic', 'link', 'bulletList', 'orderedList', 'redo', 'undo'])
                    ->maxLength(500)
                    ->columnSpanFull()
                    ->required(),

                DatePicker::make('start')
                    ->columnSpan(1)
                    ->required(),

                DatePicker::make('end')
                    ->columnSpan(1)
                    ->hint('Leave blank if current'),

                TextEntry::make('created_at')
                    ->label('Created Date')
                    ->hiddenOn('create')
                    ->state(fn(?WorkExperience $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                TextEntry::make('updated_at')
                    ->label('Last Modified Date')
                    ->hiddenOn('create')
                    ->state(fn(?WorkExperience $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(function (Table $table) {
                $count = $table->getQuery()->count();
                return $count >= 15 ? [10, 25, 50] : false;
            })
            ->columns([
                TextColumn::make('business'),
                TextColumn::make('location'),
                TextColumn::make('role'),
                TextColumn::make('start')
                    ->date(),
                TextColumn::make('end')
                    ->date(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkExperiences::route('/'),
            'create' => Pages\CreateWorkExperience::route('/create'),
            'edit' => Pages\EditWorkExperience::route('/{record}/edit'),
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
        return ['user.name'];
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
