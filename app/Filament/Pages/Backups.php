<?php

namespace App\Filament\Pages;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;
use ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups as BaseBackups;

class Backups extends BaseBackups
{
    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-cpu-chip';

    public static function getNavigationGroup(): ?string
    {
        return 'Admin';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Application Backups';
    }
}
