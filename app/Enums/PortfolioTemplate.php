<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum PortfolioTemplate: string implements HasColor, HasLabel
{

    case Standard = 'standard';

    public static function getLabels(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn(self $case) => [$case->value => $case->getLabel()])
            ->toArray();
    }

    public function getLabel(): string|Htmlable|null
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
