<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Proficiency: string implements HasLabel, HasColor
{

    case Novice = 'novice';
    case Intermediate = 'intermediate';
    case Advanced = 'advanced';
    case Mastery = 'mastery';

    public function getColor(): string
    {
        return match ($this) {
            self::Novice => 'amber',
            self::Intermediate => 'emerald',
            self::Advanced => 'cyan',
            self::Mastery => 'purple',
        };
    }

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }
}
