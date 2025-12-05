<?php

namespace App\Livewire\Atlas;

use App\Enums\Icon;
use App\Enums\PortfolioTemplate;
use App\Models\Atlas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;

class EditAtlas extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public Atlas $record;
    public ?array $data = [];

    public function mount(): void
    {
        $this->record = auth()->user()->atlas()->first() ?? new Atlas(['user_id' => auth()->id()]);

        if ($this->record->exists) {
            $this->form->fill($this->record->attributesToArray());
        }
    }

    public function form(Schema $schema): Schema
    {
        // A cleaner, responsive layout: cards + grid for compact rows and better spacing
        return $schema
            ->components([
                Section::make('Profile')
                    ->description('What shows up on your public portfolio — keep it short and friendly.')
                    ->schema([
                        Grid::make()->columns(12)->schema([
                            Textarea::make('bio')
                                ->label('Short bio')
                                ->rows(6)
                                ->columnSpanFull()
                                ->placeholder('A couple sentences about you — what you build, what you love, and a tiny fun fact.')
                                ->hint('This appears on your public profile.'),

                            ToggleButtons::make('template')
                                ->label('Template')
                                ->options(PortfolioTemplate::getLabels())
                                ->columnSpanFull()
                                ->inline()
                                ->required()
                                ->default('standard')
                                ->helperText('Choose a layout for your portfolio.'),
                        ]),
                    ]),

                Section::make('Public Links')
                    ->description('Add links people can click from your portfolio (socials, projects, resume).')
                    ->schema([
                        Repeater::make('links')
                            ->relationship('links')
                            ->orderColumn('order')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->defaultItems(1)
                            ->collapsed()
                            ->schema([
                                Grid::make()->columns(12)->schema([
                                    TextInput::make('name')
                                        ->label('Label')
                                        ->required()
                                        ->maxLength(50)
                                        ->columnSpan(4)
                                        ->placeholder('e.g. GitHub, Portfolio'),

                                    TextInput::make('url')
                                        ->label('URL')
                                        ->url()
                                        ->required()
                                        ->columnSpan(5)
                                        ->placeholder('https://'),

                                    Select::make('icon')
                                        ->label('Icon')
                                        ->options(Icon::class)
                                        ->searchable()
                                        ->columnSpan(3),


                                    TextInput::make('additional_text')
                                        ->label('Extra text')
                                        ->maxLength(255)
                                        ->columnSpan(12)
                                        ->placeholder('Optional — e.g. "Open for work" or a short note.'),

                                    Toggle::make('is_active')
                                        ->label('Visible')
                                        ->inline()
                                        ->default(true)
                                        ->columnSpan(3)
                                        ->helperText('If off, the link will be hidden from public view.'),
                                ]),
                            ])
                            ->itemLabel(fn(?array $state): string => ($state['name'] ?? 'New link') . ' — ' . (isset($state['url']) ? Str::limit($state['url'], 40) : 'no url'))
                            ->addActionLabel('New Link'),
                    ]),
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (!$this->record->exists) {
            auth()->user()->atlas()->create($data);
        } else {
            $this->record->update($data);
        }

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.atlas.edit-atlas');
    }
}
