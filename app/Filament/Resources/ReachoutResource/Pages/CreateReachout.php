<?php

namespace App\Filament\Resources\ReachoutResource\Pages;

use App\Filament\Resources\ReachoutResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReachout extends CreateRecord
{
    protected static string $resource = ReachoutResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        $data['user_id'] = $user->id;
        return $data;
    }
}
