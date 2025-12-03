<?php

namespace App\Filament\Resources\ReachoutResource\Pages;

use App\Filament\Resources\ReachoutResource;
use App\Models\Reachout;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewReachout extends ViewRecord
{
    protected static string $resource = ReachoutResource::class;

    public function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('toggleRead')
                ->label(fn(Reachout $record) => $record->is_read ? 'Mark as unread' : 'Mark as read')
                ->action(function (Reachout $record) {
                    $record->is_read = !$record->is_read;
                    $record->save();
                })
                ->color(fn(Reachout $record) => $record->is_read ? 'gray' : 'primary')
                ->icon(fn(Reachout $record) => $record->is_read ? 'heroicon-s-envelope' : 'heroicon-o-envelope-open')
        ];
    }
}
