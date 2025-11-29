<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ResumeTemplate: string implements HasLabel, HasColor
{
    case Standard = 'standard';

    public static function getLabels(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn(self $case) => [$case->value => $case->getLabel()])
            ->toArray();
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Standard => 'Standard',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Standard => 'blue',
        };
    }
}
