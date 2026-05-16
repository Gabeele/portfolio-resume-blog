<?php

namespace App\Filament\Resources;

use App\Enums\ResumeTemplate;
use App\Filament\Resources\ResumeResource\Pages;
use App\Filament\Resources\ResumeResource\RelationManagers\WorkExpereincesRelationManager;
use App\Filament\Resources\ResumeResource\RelationManagers\WorkExperiencesRelationManager;
use App\Models\Resume;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use UnitEnum;

class ResumeResource extends Resource
{
    protected static ?string $model = Resume::class;

    protected static ?string $slug = 'resumes';
    protected static string|UnitEnum|null $navigationGroup = 'Portfolio';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Resume')
                    ->collapsible()
                    ->description('Base information for this resume. Give it a clear name and add tags for grouping.')
                    ->schema([
                        TextInput::make('name')
                            ->unique(modifyRuleUsing: fn($rule) => $rule->where('user_id', auth()->id()))
                            ->placeholder('e.g. Coffee shop resume')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TagsInput::make('tags')
                            ->splitKeys(['Tab', ' '])
                            ->helperText('Use tags to organize and group resumes (e.g. "barista", "management").')
                            ->columnSpanFull()
                            ->nestedRecursiveRules([
                                'min:1',
                                'max:255',
                            ]),
                        Group::make()
                            ->columns(2)
                            ->schema([
                                Radio::make('show_avatar')
                                    ->label('Display Avatar')
                                    ->default(false)
                                    ->inline()
                                    ->boolean(),

                                ToggleButtons::make('template')
                                    ->label('Template')
                                    ->options(ResumeTemplate::getLabels())
                                    ->default(ResumeTemplate::Standard->value)
                                    ->inline()
                                    ->columnSpanFull(),
                            ])
                    ])
                    ->columns(1)
                    ->columnSpanFull(),

                Section::make('Summaries')
                    ->collapsible()
                    ->description('Short summary snippets you can include on the resume. Check the ones you want to include.')
                    ->schema([
                        CheckboxList::make('summaries')
                            ->label('Summaries')
                            ->relationship('summaries', 'body')
                            ->getOptionLabelFromRecordUsing(function (Model $record) {
                                return $record->title
                                    ?? Str::limit(strip_tags($record->body ?? ''), 100)
                                    ?? "ID {$record->id}";
                            })
                            ->columnSpanFull()
                            ->columns(2)
                            ->helperText('Choose summary snippets (title or excerpt) to show in this resume.'),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),

                Section::make('Work Experience')
                    ->collapsible()
                    ->description('Select the work experiences to include. Each option shows company, role, and date range.')
                    ->schema([
                        CheckboxList::make('work_experiences')
                            ->label('Work Experience')
                            ->relationship('workExperiences', 'business')
                            ->getOptionLabelFromRecordUsing(function (Model $record) {
                                $start = $record->start?->format('M Y') ?? '—';
                                $end = $record->end?->format('M Y') ?? 'Present';

                                return sprintf(
                                    "%s\n%s\n%s → %s",
                                    strtoupper($record->business ?? ($record->name ?? '')),
                                    $record->role ?? '',
                                    $start,
                                    $end,
                                );
                            })
                            ->columnSpanFull()
                            ->columns(2)
                            ->helperText('Check entries to include; the labels include role and date range.'),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),

                Section::make('Education')
                    ->collapsible()
                    ->description('Choose education entries to include (degree and school). Use search to find entries quickly.')
                    ->schema([
                        Select::make('education')
                            ->label('Education')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->relationship('education', 'school')
                            ->getOptionLabelFromRecordUsing(function (Model $record) {
                                $degree = $record->degree ?? null;
                                $school = $record->school ?? $record->name ?? null;

                                if ($degree && $school) {
                                    return sprintf('%s — %s', $degree, $school);
                                }

                                return $school ?? $degree ?? "ID {$record->id}";
                            })
                            ->helperText('Select one or more education entries to include.')
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
                Section::make('Projects')
                    ->collapsible()
                    ->description('Select project entries to include. Use this for featured work or portfolio items.')
                    ->schema([
                        CheckboxList::make('projects')
                            ->label('Projects')
                            ->relationship('projects', 'title')
                            ->getOptionLabelFromRecordUsing(function (Model $record) {
                                return $record->title ?? $record->name ?? "ID {$record->id}";
                            })
                            ->columnSpanFull()
                            ->columns(2)
                            ->helperText('Choose projects to display on the resume.'),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
                Section::make('Skills & Certificates')
                    ->collapsible()
                    ->description('Group related items: skills on the left and certificates on the right. Both are searchable and preloaded.')
                    ->schema([
                        Select::make('skills')
                            ->label('Skills')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->relationship('skills', 'name')
                            ->helperText('Search and select multiple skills.'),

                        Select::make('certificates')
                            ->label('Certificates')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->relationship('certificates', 'name')
                            ->helperText('Search and select certificates to include.'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('References')
                    ->collapsible()
                    ->description('Choose references to show on this resume. Typically a short list of contacts or referees.')
                    ->schema([
                        Select::make('references')
                            ->label('References')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->relationship('references', 'name')
                            ->getOptionLabelFromRecordUsing(function (Model $record) {
                                return $record->name ?? ($record->contact_name ?? "ID {$record->id}");
                            })
                            ->helperText('Select references to include.'),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),

                TextEntry::make('created_at')
                    ->label('Created Date')
                    ->hiddenOn('create')
                    ->state(fn(?Resume $record): string => $record?->created_at?->diffForHumans() ?? '-')
                    ->columnSpanFull(),

                TextEntry::make('updated_at')
                    ->label('Last Modified Date')
                    ->hiddenOn('create')
                    ->state(fn(?Resume $record): string => $record?->updated_at?->diffForHumans() ?? '-')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tags'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResumes::route('/'),
            'create' => Pages\CreateResume::route('/create'),
            'edit' => Pages\EditResume::route('/{record}/edit'),
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
        return ['name', 'user.name'];
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
