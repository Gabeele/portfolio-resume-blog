<?php

namespace App\Filament\Resources\ReachoutResource\Pages;

use App\Filament\Resources\ReachoutResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReachouts extends ListRecords
{
    protected static string $resource = ReachoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
