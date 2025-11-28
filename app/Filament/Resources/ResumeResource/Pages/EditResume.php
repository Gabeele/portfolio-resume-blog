<?php

namespace App\Filament\Resources\ResumeResource\Pages;

use App\Filament\Resources\ResumeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class EditResume extends EditRecord
{
    use HasPreviewModal;

    protected static string $resource = ResumeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviewAction::make()
                ->label('Preview Portfolio'),
            $this->getSaveFormAction()
                ->formId('form'),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getPreviewModalView(): ?string
    {
        return 'resume.preview';
    }

    protected function getPreviewModalUrl(): ?string
    {
        return route('home');
    }
}
