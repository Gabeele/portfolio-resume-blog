<?php

namespace App\Filament\Resources\WorkExperienceResource\Pages;

use App\Filament\Resources\WorkExperienceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkExperience extends CreateRecord
{
    protected static string $resource = WorkExperienceResource::class;

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
