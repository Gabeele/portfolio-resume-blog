<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $title = 'Profile';
    protected static ?string $slug = 'profile';
    protected static ?string $navigationLabel = 'Profile';
    protected static bool $shouldRegisterNavigation = false;
    public ?array $data = [];
    protected string $view = 'filament.pages.profile';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Personal Information')
                    ->description('Update your personal information and profile picture.')
                    ->schema([
                        TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('last_name')
                            ->label('Last Name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->rules([
                                'unique:users,email,' . Auth::id(),
                            ])
                            ->maxLength(255)
                            ->helperText('If you change your email, you will need to verify it again.'),

                        FileUpload::make('avatar_url')
                            ->label('Profile Picture')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->directory('avatars')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->helperText('Upload a profile picture (max 2MB).'),
                    ])
                    ->columns(2),

                Section::make('Change Password')
                    ->description('Leave blank to keep your current password.')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Current Password')
                            ->password()
                            ->revealable()
                            ->helperText('Enter your current password to change it.'),

                        TextInput::make('password')
                            ->label('New Password')
                            ->password()
                            ->minLength(8)
                            ->revealable()
                            ->confirmed()
                            ->helperText('Password must be at least 8 characters.'),

                        TextInput::make('password_confirmation')
                            ->label('Confirm New Password')
                            ->password()
                            ->revealable()
                            ->requiredWith('password'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ])
            ->statePath('data')
            ->model(Auth::user());
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $user = Auth::user();

        if (!empty($data['password'])) {
            if (!empty($data['current_password'])) {
                if (!Auth::guard()->validate([
                    'email' => $user->email,
                    'password' => $data['current_password'],
                ])) {
                    Notification::make()
                        ->title('Current password is incorrect')
                        ->danger()
                        ->send();

                    return;
                }
            }
        } else {
            unset($data['password']);
        }

        unset($data['password_confirmation'], $data['current_password']);

        if (isset($data['email']) && $data['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $data['email_verified_at'] = null;
        }

        $user->update($data);

        Notification::make()
            ->title('Profile updated successfully')
            ->success()
            ->send();

        $this->mount();
    }

    public function mount(): void
    {
        $user = Auth::user();
        $this->form->fill([
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'avatar_url' => $user->avatar_url,
        ]);
    }
}
