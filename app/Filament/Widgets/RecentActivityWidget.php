<?php

namespace App\Filament\Widgets;

use App\Models\Analytic;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class RecentActivityWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Analytic::query()
                    ->where('user_id', Auth::id())
                    ->with(['trackable'])
                    ->latest()
            )
            ->columns([
                TextColumn::make('event_type')
                    ->label('Event')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'portfolio_view' => 'Portfolio View',
                        'blog_view' => 'Blog Page View',
                        'blog_post_view' => 'Blog Post View',
                        'links_view' => 'Links Page View',
                        'resume_download' => 'Resume Download',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'portfolio_view' => 'info',
                        'blog_view', 'blog_post_view' => 'success',
                        'links_view' => 'warning',
                        'resume_download' => 'primary',
                        default => 'gray',
                    }),

                TextColumn::make('trackable.title')
                    ->label('Resource')
                    ->default('—')
                    ->limit(40),

                TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->toggleable()
                    ->default('—'),

                TextColumn::make('referer')
                    ->label('Referrer')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->default('—'),

                TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime()
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50])
            ->heading('Recent Activity');
    }
}
