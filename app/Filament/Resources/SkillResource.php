<?php

namespace App\Filament\Resources;

use App\Enums\Proficiency;
use App\Filament\Resources\SkillResource\Pages;
use App\Models\Skill;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class SkillResource extends Resource
{
    protected static ?string $model = Skill::class;

    protected static ?string $slug = 'skills';

    protected static string|UnitEnum|null $navigationGroup = 'Portfolio';
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->unique(modifyRuleUsing: fn($rule) => $rule->where('user_id', auth()->id()))
                    ->required(),

                ToggleButtons::make('proficiency')
                    ->options(Proficiency::class)
                    ->grouped()
                    ->inline(),

                RichEditor::make('additional_evidence')
                    ->label('Additional Comments')
                    ->maxLength(500)
                    ->helperText('Provide any additional information or evidence related to this skill.')
                    ->toolbarButtons(['bold', 'underline', 'italic', 'link', 'bulletList', 'orderedList', 'redo', 'undo'])
                    ->columnSpanFull(),

                TextEntry::make('created_at')
                    ->label('Created Date')
                    ->hiddenOn('create')
                    ->state(fn(?Skill $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                TextEntry::make('updated_at')
                    ->label('Last Modified Date')
                    ->hiddenOn('create')
                    ->state(fn(?Skill $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),

                TextColumn::make('proficiency')
                    ->badge(),

                TextColumn::make('additional_evidence')
                    ->label('Additional Comments')
                    ->html()
                    ->limit(50),
            ])
            ->paginated(fn(Table $table): int => 10);;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSkills::route('/'),
            'create' => Pages\CreateSkill::route('/create'),
            'edit' => Pages\EditSkill::route('/{record}/edit'),
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
