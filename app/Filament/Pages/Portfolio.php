<?php

namespace App\Filament\Pages;

use App\Enums\PortfolioTemplate;
use App\Rules\MailingCodeRule;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Portfolio extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-user';

    protected static ?string $title = 'Portfolio';

    protected static ?string $slug = 'portfolio';

    protected static ?string $navigationLabel = 'Portfolio';

    protected static ?int $navigationSort = 0;

    public ?array $data = [];

    protected string $view = 'filament.pages.portfolio';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Portfolio URL')
                    ->description('This is the unique identifier for your portfolio. It will be used in the public URL.')
                    ->schema([
                        TextInput::make('slug')
                            ->label('Your Portfolio Slug')
                            ->required()
                            ->prefix(config('app.url') . '/')
                            ->unique(
                                table: 'users',
                                column: 'slug',
                                ignoreRecord: true,
                            )
                            ->suffixAction(
                                Action::make('visit')
                                    ->icon('heroicon-m-arrow-top-right-on-square')
                                    ->url(fn(Get $get) => config('app.url') . '/' . $get('slug'), shouldOpenInNewTab: true)
                                    ->visible(fn(Get $get) => filled($get('slug')))
                            )
                            ->helperText('Choose a unique URL for your portfolio')
                            ->columnSpanFull(),
                    ]),

                Section::make('Design & Template')
                    ->description('Select the design template for your portfolio.')
                    ->schema([
                        ToggleButtons::make('template')
                            ->label('Portfolio Template')
                            ->options(PortfolioTemplate::getLabels())
                            ->required()
                            ->inline()
                            ->columnSpanFull(),
                    ]),

                Section::make('Public Resume')
                    ->description('Select the resume that will be publicly displayed on your portfolio.')
                    ->schema([
                        Select::make('public_resume_id')
                            ->label('Public Facing Resume')
                            ->placeholder('Select a resume to display publicly')
                            ->relationship('publicResume', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Choose which resume visitors will see on your portfolio. Leave blank to hide resume.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Personal Information')
                    ->description('This information will be displayed publicly on your portfolio.')
                    ->schema([
                        Grid::make(5)
                            ->schema([
                                FileUpload::make('avatar_url')
                                    ->label('Profile Picture')
                                    ->avatar()
                                    ->image()
                                    ->imageEditor()
                                    ->circleCropper()
                                    ->directory('avatars')
                                    ->visibility('public')
                                    ->maxSize(2048)
                                    ->helperText('Upload a profile picture (max 2MB).')
                                    ->columnSpan(1),

                                Group::make()
                                    ->schema([
                                        TextInput::make('first_name')
                                            ->label('First Name')
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('last_name')
                                            ->label('Last Name')
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('phone')
                                            ->tel()
                                            ->maxLength(50)
                                            ->helperText('Optional contact number'),
                                    ])
                                    ->columnSpan(4),
                            ]),

                        Textarea::make('bio')
                            ->label('Short Bio')
                            ->rows(6)
                            ->columnSpanFull()
                            ->placeholder('A couple sentences about you — what you build, what you love, and a tiny fun fact.')
                            ->helperText('This appears on your public portfolio.'),
                    ]),

                Section::make('Public Links')
                    ->description('Add links people can click from your portfolio (socials, projects, resume).')
                    ->schema([
                        Repeater::make('links')
                            ->relationship('links')
                            ->orderColumn('order')
                            ->reorderableWithButtons()
                            ->defaultItems(0)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Link Name')
                                    ->required()
                                    ->maxLength(50)
                                    ->placeholder('e.g. GitHub, LinkedIn, Portfolio'),

                                TextInput::make('url')
                                    ->label('URL')
                                    ->url()
                                    ->required()
                                    ->placeholder('https://'),

                                TextInput::make('description')
                                    ->label('Description (optional)')
                                    ->maxLength(255)
                                    ->placeholder('e.g. "Open for work" or a short note'),

                                Toggle::make('is_active')
                                    ->label('Show on portfolio')
                                    ->inline(false)
                                    ->default(true),
                            ])
                            ->columns(2)
                            ->itemLabel(fn(?array $state): string => ($state['name'] ?? 'New link') . ' — ' . (isset($state['url']) ? Str::limit($state['url'], 40) : 'no url'))
                            ->addActionLabel('Add Link')
                            ->deleteAction(
                                fn($action) => $action->requiresConfirmation()
                            ),
                    ]),

                Section::make('Address')
                    ->description('Address information will be displayed publicly on your portfolio. Leave blank to hide.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('street')
                            ->maxLength(255),

                        TextInput::make('city')
                            ->maxLength(255),

                        TextInput::make('region')
                            ->label('Province / State')
                            ->maxLength(255),

                        TextInput::make('mailing_code')
                            ->label('Postal / ZIP Code')
                            ->rules([new MailingCodeRule])
                            ->maxLength(20),

                        Select::make('country')
                            ->native(false)
                            ->options([
                                'Canada' => 'Canada',
                                'United States' => 'United States',
                                'United Kingdom' => 'United Kingdom',
                            ])
                            ->default('Canada'),
                    ]),
            ])
            ->statePath('data')
            ->model(Auth::user());
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $user = Auth::user();

        try {
            $user->update($data);

            Notification::make()
                ->title('Portfolio updated successfully')
                ->success()
                ->send();

            $this->mount();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Unable to update portfolio')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function mount(): void
    {
        $user = Auth::user()->load('publicResume', 'links');

        $this->form->fill([
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'phone' => $user->phone,
            'street' => $user->street,
            'city' => $user->city,
            'region' => $user->region,
            'mailing_code' => $user->mailing_code,
            'country' => $user->country ?? 'Canada',
            'avatar_url' => $user->avatar_url,
            'bio' => $user->bio,
            'slug' => $user->slug,
            'template' => $user->template,
            'public_resume_id' => $user->public_resume_id,
        ]);
    }
}
