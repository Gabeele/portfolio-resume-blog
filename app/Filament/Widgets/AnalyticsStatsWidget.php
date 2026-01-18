<?php

namespace App\Filament\Widgets;

use App\Models\Analytic;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class AnalyticsStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();

        // Last 30 days
        $thirtyDaysAgo = now()->subDays(30);
        $sixtyDaysAgo = now()->subDays(60);

        // Current period (last 30 days)
        $portfolioViews = Analytic::where('user_id', $user->id)
            ->where('event_type', 'portfolio_view')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count();

        $blogViews = Analytic::where('user_id', $user->id)
            ->whereIn('event_type', ['blog_view', 'blog_post_view'])
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count();

        $linksViews = Analytic::where('user_id', $user->id)
            ->where('event_type', 'links_view')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count();

        $resumeDownloads = Analytic::where('user_id', $user->id)
            ->where('event_type', 'resume_download')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count();

        // Previous period (30-60 days ago)
        $portfolioViewsPrevious = Analytic::where('user_id', $user->id)
            ->where('event_type', 'portfolio_view')
            ->whereBetween('created_at', [$sixtyDaysAgo, $thirtyDaysAgo])
            ->count();

        $blogViewsPrevious = Analytic::where('user_id', $user->id)
            ->whereIn('event_type', ['blog_view', 'blog_post_view'])
            ->whereBetween('created_at', [$sixtyDaysAgo, $thirtyDaysAgo])
            ->count();

        $linksViewsPrevious = Analytic::where('user_id', $user->id)
            ->where('event_type', 'links_view')
            ->whereBetween('created_at', [$sixtyDaysAgo, $thirtyDaysAgo])
            ->count();

        $resumeDownloadsPrevious = Analytic::where('user_id', $user->id)
            ->where('event_type', 'resume_download')
            ->whereBetween('created_at', [$sixtyDaysAgo, $thirtyDaysAgo])
            ->count();

        return [
            Stat::make('Portfolio Views', $portfolioViews)
                ->description($this->getTrendDescription($portfolioViews, $portfolioViewsPrevious))
                ->descriptionIcon($this->getTrendIcon($portfolioViews, $portfolioViewsPrevious))
                ->color($this->getTrendColor($portfolioViews, $portfolioViewsPrevious))
                ->chart($this->getChartData('portfolio_view', 7)),

            Stat::make('Blog Views', $blogViews)
                ->description($this->getTrendDescription($blogViews, $blogViewsPrevious))
                ->descriptionIcon($this->getTrendIcon($blogViews, $blogViewsPrevious))
                ->color($this->getTrendColor($blogViews, $blogViewsPrevious))
                ->chart($this->getChartData(['blog_view', 'blog_post_view'], 7)),

            Stat::make('Links Page Views', $linksViews)
                ->description($this->getTrendDescription($linksViews, $linksViewsPrevious))
                ->descriptionIcon($this->getTrendIcon($linksViews, $linksViewsPrevious))
                ->color($this->getTrendColor($linksViews, $linksViewsPrevious))
                ->chart($this->getChartData('links_view', 7)),

            Stat::make('Resume Downloads', $resumeDownloads)
                ->description($this->getTrendDescription($resumeDownloads, $resumeDownloadsPrevious))
                ->descriptionIcon($this->getTrendIcon($resumeDownloads, $resumeDownloadsPrevious))
                ->color($this->getTrendColor($resumeDownloads, $resumeDownloadsPrevious))
                ->chart($this->getChartData('resume_download', 7)),
        ];
    }

    protected function getTrendDescription(int $current, int $previous): string
    {
        if ($previous === 0) {
            return $current > 0 ? 'New activity' : 'No activity yet';
        }

        $percentChange = round((($current - $previous) / $previous) * 100);

        if ($percentChange > 0) {
            return "{$percentChange}% increase";
        } elseif ($percentChange < 0) {
            return abs($percentChange) . '% decrease';
        }

        return 'No change';
    }

    protected function getTrendIcon(int $current, int $previous): string
    {
        if ($previous === 0) {
            return $current > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-minus';
        }

        if ($current > $previous) {
            return 'heroicon-m-arrow-trending-up';
        } elseif ($current < $previous) {
            return 'heroicon-m-arrow-trending-down';
        }

        return 'heroicon-m-minus';
    }

    protected function getTrendColor(int $current, int $previous): string
    {
        if ($current > $previous) {
            return 'success';
        } elseif ($current < $previous) {
            return 'danger';
        }

        return 'gray';
    }

    protected function getChartData(string|array $eventTypes, int $days = 7): array
    {
        $user = Auth::user();
        $eventTypes = is_array($eventTypes) ? $eventTypes : [$eventTypes];

        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $count = Analytic::where('user_id', $user->id)
                ->whereIn('event_type', $eventTypes)
                ->whereDate('created_at', $date)
                ->count();
            $data[] = $count;
        }

        return $data;
    }
}
