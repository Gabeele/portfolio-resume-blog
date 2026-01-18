<?php

namespace App\Filament\Widgets;

use App\Models\Analytic;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class AnalyticsChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;
    public ?string $filter = 'all';
    protected ?string $heading = 'Activity Over Last 30 Days';

    protected function getData(): array
    {
        $user = Auth::user();
        $days = 30;

        $labels = [];
        $portfolioData = [];
        $blogData = [];
        $linksData = [];
        $resumeData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $labels[] = $date->format('M d');

            $portfolioData[] = Analytic::where('user_id', $user->id)
                ->where('event_type', 'portfolio_view')
                ->whereDate('created_at', $date)
                ->count();

            $blogData[] = Analytic::where('user_id', $user->id)
                ->whereIn('event_type', ['blog_view', 'blog_post_view'])
                ->whereDate('created_at', $date)
                ->count();

            $linksData[] = Analytic::where('user_id', $user->id)
                ->where('event_type', 'links_view')
                ->whereDate('created_at', $date)
                ->count();

            $resumeData[] = Analytic::where('user_id', $user->id)
                ->where('event_type', 'resume_download')
                ->whereDate('created_at', $date)
                ->count();
        }

        $datasets = match ($this->filter) {
            'portfolio' => [
                [
                    'label' => 'Portfolio Views',
                    'data' => $portfolioData,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
            ],
            'blog' => [
                [
                    'label' => 'Blog Views',
                    'data' => $blogData,
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                ],
            ],
            'links' => [
                [
                    'label' => 'Links Views',
                    'data' => $linksData,
                    'borderColor' => 'rgb(245, 158, 11)',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                ],
            ],
            'resume' => [
                [
                    'label' => 'Resume Downloads',
                    'data' => $resumeData,
                    'borderColor' => 'rgb(139, 92, 246)',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                ],
            ],
            default => [
                [
                    'label' => 'Portfolio Views',
                    'data' => $portfolioData,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
                [
                    'label' => 'Blog Views',
                    'data' => $blogData,
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                ],
                [
                    'label' => 'Links Views',
                    'data' => $linksData,
                    'borderColor' => 'rgb(245, 158, 11)',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                ],
                [
                    'label' => 'Resume Downloads',
                    'data' => $resumeData,
                    'borderColor' => 'rgb(139, 92, 246)',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                ],
            ],
        };

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'all' => 'All Activity',
            'portfolio' => 'Portfolio Only',
            'blog' => 'Blog Only',
            'links' => 'Links Only',
            'resume' => 'Resume Downloads',
        ];
    }
}
