<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use UnitEnum as UnitEnumAlias;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $slug = 'posts';

    protected static string|null|UnitEnumAlias $navigationGroup = 'Blog';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make('Post Details')
                    ->description('Basic information about the post.')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn($state, $set) => $set('slug', Str::slug($state)))
                            ->maxLength(255)
                            ->placeholder('A clear, descriptive title'),

                        TextInput::make('slug')
                            ->required()
                            ->disabled(fn(?Model $record) => (bool)$record) // disabled on edit
                            ->unique(Post::class, 'slug', fn($record) => $record)
                            ->maxLength(255)
                            ->hint('URL friendly, auto-filled from title'),

                        Select::make('tags')
                            ->multiple()
                            ->preload()
                            ->relationship('tags', 'name')
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                            ])
                            ->hint('Select or create tags')
                            ->createOptionUsing(function (array $data): int {
                                return auth()->user()->tags()->create($data)->getKey();
                            }),
                    ]),

                Section::make('Content')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Builder::make('body')
                            ->label('Body / Content')
                            ->helperText('Use blocks to structure headings, paragraphs, images, embeds, quotes, code, and galleries.')
                            ->collapsible()
                            ->reorderable()
                            ->maxItems(100)
                            ->blocks([
                                Block::make('heading')
                                    ->label('Heading')
                                    ->schema([
                                        TextInput::make('content')
                                            ->label('Text')
                                            ->required()
                                            ->maxLength(255),
                                        Select::make('level')
                                            ->label('Level')
                                            ->options([
                                                'h1' => 'H1',
                                                'h2' => 'H2',
                                                'h3' => 'H3',
                                                'h4' => 'H4',
                                                'h5' => 'H5',
                                                'h6' => 'H6',
                                            ])
                                            ->required(),
                                    ])
                                    ->columns(2),

                                Block::make('paragraph')
                                    ->label('Paragraph')
                                    ->schema([
                                        Textarea::make('content')
                                            ->label('Text')
                                            ->rows(6)
                                            ->required()
                                            ->placeholder('Write an engaging paragraph...'),
                                    ]),

                                Block::make('image')
                                    ->label('Image')
                                    ->schema([
                                        FileUpload::make('url')
                                            ->label('Image')
                                            ->image()
                                            ->required()
                                            ->disk('public')
                                            ->directory('posts/images')
                                            ->imagePreviewHeight(250)
                                            ->maxSize(2048) // 2 MB
                                            ->extraAttributes(['accept' => 'image/*'])
                                            ->hint('Recommended: use images under 2MB'),
                                        TextInput::make('alt')
                                            ->label('Alt text')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Describe the image for accessibility')
                                            ->afterStateHydrated(fn($state, $set, $get) => $state ?: null)
                                            ->afterStateUpdated(function ($state, $set, $get, $record, $component) {
                                                if (empty($state)) {
                                                    $file = $get('url');
                                                    if (is_string($file)) {
                                                        $filename = pathinfo($file, PATHINFO_FILENAME);
                                                        if ($filename) {
                                                            $set('alt', Str::headline(str_replace(['-', '_'], ' ', $filename)));
                                                        }
                                                    }
                                                }
                                            }),
                                    ]),

                                Block::make('gallery')
                                    ->label('Image Gallery')
                                    ->schema([
                                        FileUpload::make('images')
                                            ->label('Gallery images')
                                            ->multiple()
                                            ->image()
                                            ->disk('public')
                                            ->directory('posts/galleries')
                                            ->imagePreviewHeight(120)
                                            ->maxFiles(8)
                                            ->maxSize(2048)
                                            ->hint('Up to 8 images, 2MB each'),
                                        TextInput::make('caption')
                                            ->label('Gallery caption')
                                            ->maxLength(255),
                                    ]),

                                Block::make('quote')
                                    ->label('Quote')
                                    ->schema([
                                        Textarea::make('content')
                                            ->label('Quote text')
                                            ->rows(3)
                                            ->required(),
                                        TextInput::make('cite')
                                            ->label('Attribution')
                                            ->maxLength(255),
                                    ]),

                                Block::make('embed')
                                    ->label('Embed (YouTube / Tweet / etc.)')
                                    ->schema([
                                        TextInput::make('url')
                                            ->label('Embed URL')
                                            ->required()
                                            ->url()
                                            ->placeholder('https://www.youtube.com/watch?v=...'),
                                        TextInput::make('caption')
                                            ->label('Caption')
                                            ->maxLength(255),
                                    ]),

                                Block::make('code')
                                    ->label('Code')
                                    ->schema([
                                        Select::make('language')
                                            ->label('Language')
                                            ->options([
                                                'php' => 'PHP',
                                                'js' => 'JavaScript',
                                                'css' => 'CSS',
                                                'html' => 'HTML',
                                                'bash' => 'Bash',
                                            ])
                                            ->default('php'),
                                        Textarea::make('content')
                                            ->label('Code')
                                            ->rows(8)
                                            ->required(),
                                    ]),

                                Block::make('callout')
                                    ->label('Callout')
                                    ->schema([
                                        TextInput::make('title')->label('Title')->maxLength(120),
                                        Textarea::make('content')->label('Content')->rows(3)->required(),
                                        Select::make('type')
                                            ->label('Type')
                                            ->options([
                                                'info' => 'Info',
                                                'warning' => 'Warning',
                                                'success' => 'Success',
                                            ])
                                            ->default('info'),
                                    ])
                                    ->columns(1),
                            ])
                            ->columns(1)
                    ]),

                Section::make('SEO')
                    ->collapsible()
                    ->description('Search engine optimization settings.')
                    ->schema([
                        TextInput::make('meta_title')
                            ->maxLength(70)
                            ->placeholder('Optional — up to 70 characters')
                            ->helperText('If empty, the post title may be used by some templates.'),

                        TextInput::make('meta_description')
                            ->maxLength(160)
                            ->placeholder('Short summary for search engines (150–160 chars recommended)'),

                        TextInput::make('meta_keywords')
                            ->placeholder('comma, separated, keywords')
                            ->maxLength(255),
                    ]),

                Section::make('Publishing')
                    ->collapsible()
                    ->columns(2)
                    ->schema([
                        Toggle::make('publish')
                            ->label('Publish')
                            ->reactive(),

                        DatePicker::make('published_at')
                            ->label('Published Date')
                            ->helperText('Set a publish date (optional)')
                            ->visible(fn($get) => (bool)$get('publish')),
                    ]),

                Section::make('Timestamps')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created Date')
                            ->hiddenOn('create')
                            ->state(fn(?Post $record) => $record?->created_at?->diffForHumans() ?? '-'),
                        TextEntry::make('updated_at')
                            ->label('Last Modified Date')
                            ->hiddenOn('create')
                            ->state(fn(?Post $record) => $record?->updated_at?->diffForHumans() ?? '-'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('slug')
                    ->copyable()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('body')
                    ->label('Excerpt')
                    ->getStateUsing(function ($record) {
                        if (is_string($record->body)) {
                            return Str::limit(strip_tags($record->body), 120);
                        }

                        if (is_array($record->body) && isset($record->body['blocks'])) {
                            foreach ($record->body['blocks'] as $block) {
                                if (!empty($block['content']) && is_string($block['content'])) {
                                    return Str::limit(strip_tags($block['content']), 120);
                                }
                            }
                            return Str::limit(json_encode($record->body), 120);
                        }

                        return '-';
                    })
                    ->wrap()
                    ->toggleable(),

                ToggleColumn::make('publish')
                    ->label('Published')
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('Published Date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Filter::make('published')
                    ->query(fn(Builder $query) => $query->where('publish', true))
                    ->label('Published'),
                Filter::make('unpublished')
                    ->query(fn(Builder $query) => $query->where('publish', false))
                    ->label('Unpublished'),
            ])
            ->defaultSort('published_at', 'desc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user', 'tags']);
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['user']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug', 'user.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->user) {
            $details['User'] = $record->user->name;
        }

        if ($record->published_at) {
            $details['Published'] = $record->published_at->toDateString();
        }

        return $details;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}

