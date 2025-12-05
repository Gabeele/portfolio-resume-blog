<?php

namespace App\Enums;

use Illuminate\Support\HtmlString;

enum Icon: string
{
    case Github = 'github';
    case Twitter = 'twitter';
    case Linkedin = 'linkedin';
    case Website = 'website';
    case Email = 'email';

    /**
     * Return a rendered SVG/HTML for this icon.
     */
    public function svg(): HtmlString
    {
        // We use Blade partials stored at resources/views/icons/{value}.blade.php
        // e.g. resources/views/icons/github.blade.php
        $view = view("icons.{$this->value}");

        // Render and return as HtmlString so Filament->allowHtml() can display it.
        return new HtmlString($view->render());
    }

    /**
     * A readable label for UI.
     */
    public function label(): string
    {
        return match ($this) {
            self::Github => 'GitHub',
            self::Twitter => 'Twitter',
            self::Linkedin => 'LinkedIn',
            self::Website => 'Website',
            self::Email => 'Email',
        };
    }

    /**
     * Optional helper for Filament prefixIcon compatibility (if you use heroicon naming).
     */
    public function prefixKey(): string
    {
        return "custom-{$this->value}";
    }
}
