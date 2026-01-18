<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class QuickLinksWidget extends Widget
{
    protected static ?int $sort = 0;
    protected string $view = 'filament.widgets.quick-links-widget';
    protected int|string|array $columnSpan = 'full';

    public function getLinks(): array
    {
        $user = Auth::user();

        $links = [
            [
                'label' => 'Portfolio',
                'url' => $user->siteUrl(),
                'icon' => 'heroicon-o-user',
                'color' => 'info',
                'description' => 'Your public portfolio page',
            ],
            [
                'label' => 'Blog',
                'url' => $user->blogUrl(),
                'icon' => 'heroicon-o-newspaper',
                'color' => 'success',
                'description' => 'Your blog listing page',
            ],
            [
                'label' => 'Links',
                'url' => $user->linksUrl(),
                'icon' => 'heroicon-o-link',
                'color' => 'warning',
                'description' => 'Your links/linktree page',
            ],
        ];

        // Add resume PDF link if user has a public resume
        if ($user->publicResume) {
            $links[] = [
                'label' => 'Resume PDF',
                'url' => route('pdf.resume', $user->publicResume),
                'icon' => 'heroicon-o-document-text',
                'color' => 'primary',
                'description' => 'Direct link to your resume PDF',
            ];
        }

        return $links;
    }
}
