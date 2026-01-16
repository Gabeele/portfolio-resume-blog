<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        $data['user_id'] = $user->id;

        if (!empty($data['slug'])) {
            $data['slug'] = Str::slug($data['slug']);
        } elseif (!empty($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        return $data;
    }
}
