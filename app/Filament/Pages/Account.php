<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Account extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $title = 'Account';

    protected static ?string $slug = 'account';

    protected static ?string $navigationLabel = 'Account';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 99;
    public ?array $data = [];
    protected string $view = 'filament.pages.account';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Email Address')
                    ->description('Update your email address for account notifications and login.')
                    ->schema([
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->rules([
                                Rule::unique('users', 'email')->ignore(Auth::id()),
                            ])
                            ->maxLength(255)
                            ->helperText('We will send a verification email if you change your email address.')
                            ->columnSpanFull(),
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
                            ->helperText('Enter your current password to change it.')
                            ->columnSpan(2),

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
                ->title('Account updated successfully')
                ->success()
                ->send();

            $this->mount();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Unable to update account')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function mount(): void
    {
        $user = Auth::user();

        $this->form->fill([
            'email' => $user->email,
        ]);
    }
}
