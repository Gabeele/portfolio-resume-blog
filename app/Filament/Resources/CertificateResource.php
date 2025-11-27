<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificateResource\Pages;
use App\Models\Certificate;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;
    protected static ?string $slug = 'certificates';
    protected static string|UnitEnum|null $navigationGroup = 'Portfolio';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->columnSpanFull()
                    ->required(),

                RichEditor::make('description')
                    ->toolbarButtons(['bold', 'underline', 'italic', 'link', 'bulletList', 'orderedList', 'redo', 'undo'])
                    ->columnSpanFull()
                    ->required(),

                FileUpload::make('file')
                    ->directory('certificates')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(1024)
                    ->maxFiles(1)
                    ->helperText('Upload a PDF certificate file.')
                    ->columnSpanFull(),

                TextInput::make('url')
                    ->helperText('Provide a link to the certificate if available.')
                    ->url()
                    ->columnSpanFull(),

                TextEntry::make('created_at')
                    ->label('Created Date')
                    ->hiddenOn('create')
                    ->state(fn(?Certificate $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                TextEntry::make('updated_at')
                    ->label('Last Modified Date')
                    ->hiddenOn('create')
                    ->state(fn(?Certificate $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    TextColumn::make('name'),
                    TextColumn::make('created_at')
                        ->label('Created')
                        ->dateTime(),
                ]),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->paginated(fn(Table $table): int => 10);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCertificates::route('/'),
            'create' => Pages\CreateCertificate::route('/create'),
            'edit' => Pages\EditCertificate::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
