<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferenceResource\Pages;
use App\Models\Reference;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class ReferenceResource extends Resource
{
    protected static ?string $model = Reference::class;

    protected static ?string $slug = 'references';

    protected static string|UnitEnum|null $navigationGroup = 'Portfolio';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                TextInput::make('title')
                    ->required(),

                TextInput::make('company')
                    ->required(),

                TextInput::make('phone'),

                TextInput::make('email'),

                TextEntry::make('created_at')
                    ->label('Created Date')
                    ->hiddenOn('create')
                    ->state(fn(?Reference $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                TextEntry::make('updated_at')
                    ->label('Last Modified Date')
                    ->hiddenOn('create')
                    ->state(fn(?Reference $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),

                TextColumn::make('title'),

                TextColumn::make('phone'),

                TextColumn::make('email'),

                TextColumn::make('company'),
            ])
            ->paginated(fn(Table $table): int => 10);;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReferences::route('/'),
            'create' => Pages\CreateReference::route('/create'),
            'edit' => Pages\EditReference::route('/{record}/edit'),
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
        return ['name', 'title', 'email', 'user.name'];
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
