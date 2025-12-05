<?php

namespace App\Filament\Resources;

use App\Filament\Actions\EmailAction;
use App\Filament\Resources\ReachoutResource\Pages;
use App\Models\Reachout;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class ReachoutResource extends Resource
{
    protected static ?string $model = Reachout::class;

    protected static ?string $slug = 'reachouts';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenter;

    public static function getNavigationBadge(): ?string
    {
        $count = Reachout::unread()->count();

        return $count > 0 ? (string)$count : null;
    }


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Full name')
                    ->required()
                    ->columnSpan('full'),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->copyable()
                    ->columnSpan('full'),

                TextInput::make('message')
                    ->label('Message')
                    ->required()
                    ->columnSpan('full'),

                TextEntry::make('created_at')
                    ->label('Created Date')
                    ->hiddenOn('create')
                    ->state(fn(?Reachout $record): string => $record?->created_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('is_read')
                    ->label('Read')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-s-envelope')
                    ->trueColor('gray')
                    ->falseColor('primary'),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->url(fn($record) => "mailto:{$record->email}")
                    ->openUrlInNewTab(),

                TextColumn::make('message')
                    ->label('Message')
                    ->limit(100)
                    ->wrap()
                    ->tooltip(fn($record) => $record->message),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->diffForHumans() : '-')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_read')
                    ->label('Read Status')
                    ->boolean()
                    ->trueLabel('Read')
                    ->falseLabel('Unread')
                    ->nullable(),

                TrashedFilter::make(),
            ])
            ->recordActions([
                EmailAction::make()
                    ->label('Reply'),
                ViewAction::make()
                    ->label(''),
                DeleteAction::make()
                    ->label(''),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReachouts::route('/'),
            'view' => Pages\ViewReachout::route('/{record}'),
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
        return ['name', 'email'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->user) {
            $details['User'] = $record->user->email;
        }

        return $details;
    }
}
