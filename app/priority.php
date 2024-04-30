<?php

namespace App;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Colors\Color;

enum priority: string implements HasLabel,HasIcon,HasColor
{
    case High = 'high';
    case Medium = 'medium';
    case Low = 'low';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::High => 'High',
            self::Medium => 'Medium',
            self::Low => 'Low',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::High => Color::Red,
            self::Medium =>  'info',
            self::Low => Color::Emerald,
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::High => 'carbon-skill-level-advanced',
            self::Medium => 'carbon-skill-level-intermediate',
            self::Low => 'carbon-skill-level-basic',
        };
    }
}
