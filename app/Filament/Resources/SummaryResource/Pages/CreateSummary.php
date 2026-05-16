<?php

namespace App\Filament\Resources\SummaryResource\Pages;

use App\Filament\Resources\SummaryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSummary extends CreateRecord
{
    protected static string $resource = SummaryResource::class;

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
