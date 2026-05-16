<?php

namespace App\Filament\Pages;

use App\Rules\MailingCodeRule;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $title = 'Profile';
    protected static ?string $slug = 'profile';
    protected static ?string $navigationLabel = 'Profile';
    protected static bool $shouldRegisterNavigation = false;
    protected string $view = 'filament.pages.profile';

    public ?array $data = [];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Personal Information')
                    ->description('Update your personal information and profile picture.')
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

                                        TextInput::make('email')
                                            ->label('Email Address')
                                            ->email()
                                            ->required()
                                            ->rules([
                                                Rule::unique('users', 'email')->ignore(Auth::id()),
                                            ])
                                            ->maxLength(255),

                                        TextInput::make('phone')
                                            ->tel()
                                            ->maxLength(50),
                                    ])
                                    ->columnSpan(4),
                            ]),
                    ]),

                Section::make('Address')
                    ->description('Address information will be displayed publicly. Leave blank to hide.')
                    ->columns(2)
                    ->schema([


                        TextInput::make('street')
                            ->maxLength(255),

                        TextInput::make('city')
                            ->maxLength(255),

                        TextInput::make('region')
                            ->label('Province / State')
                            ->reactive()
                            ->maxLength(255),

                        TextInput::make('mailing_code')
                            ->label('Postal / ZIP Code')
                            ->rules([new MailingCodeRule()])
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

                Section::make('Change Password')
                    ->description('Leave blank to keep your current password.')
                    ->columns(2)
                    ->collapsible()
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
                    ]),
            ])
            ->statePath('data')
            ->model(Auth::user());
    }

    public function mount(): void
    {
        $user = Auth::user();

        $this->form->fill([
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'street' => $user->street,
            'city' => $user->city,
            'region' => $user->region,
            'mailing_code' => $user->mailing_code,
            'country' => $user->country ?? 'Canada',
            'avatar_url' => $user->avatar_url,
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        /** @var Authenticatable $user */
        $user = Auth::user();

        if (!empty($data['password'])) {
            if (empty($data['current_password']) || !Hash::check($data['current_password'], $user->password)) {
                Notification::make()
                    ->title('Current password is incorrect or missing')
                    ->danger()
                    ->send();

                return;
            }

            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        unset($data['password_confirmation'], $data['current_password']);

        if (isset($data['email']) && $data['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $data['email_verified_at'] = null;
        }

        try {
            $user->update($data);

            if (isset($data['email']) && $data['email'] !== $user->email && method_exists($user, 'sendEmailVerificationNotification')) {
                $user->sendEmailVerificationNotification();
            }

            Notification::make()
                ->title('Profile updated successfully')
                ->success()
                ->send();

            $this->mount();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Unable to update profile')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
