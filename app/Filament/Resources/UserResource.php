<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'users';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::User;

    protected static string|UnitEnum|null $navigationGroup = 'Admin';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),

                TextInput::make('email')
                    ->required(),

                DatePicker::make('email_verified_at')
                    ->disabled()
                    ->label('Email Verified Date'),

                Grid::make()
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created Date')
                            ->state(fn(?User $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                        TextEntry::make('updated_at')
                            ->label('Last Modified Date')
                            ->state(fn(?User $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                        TextEntry::make('two_factor_confirmed_at')
                            ->label('Two Factor Confirmed Date')
                            ->state(fn(?User $record): string => $record?->two_factor_confirmed_at?->diffForHumans() ?? '-'),

                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('roles.name'),

                TextColumn::make('email_verified_at')
                    ->label('Email Verified Date')
                    ->date(),

                TextColumn::make('two_factor_secret'),

                TextColumn::make('two_factor_recovery_codes'),

                TextColumn::make('two_factor_confirmed_at')
                    ->label('Two Factor Confirmed Date')
                    ->date(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
