<?php

namespace App\Filament\Resources\ReachoutResource\Pages;

use App\Filament\Resources\ReachoutResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditReachout extends EditRecord
{
    protected static string $resource = ReachoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
