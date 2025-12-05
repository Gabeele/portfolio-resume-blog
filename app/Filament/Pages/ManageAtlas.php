<?php

namespace App\Filament\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class ManageAtlas extends Page
{
    use InteractsWithForms;

    protected static string|null|\BackedEnum $navigationIcon = HeroIcon::OutlinedMap;
    protected string $view = 'filament.pages.manage-atlas';
}
