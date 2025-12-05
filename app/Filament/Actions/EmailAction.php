<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;

class EmailAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->name('email');

        $this->label('Reply in Mail');

        $this->url(fn($record) => "mailto:{$record->email}")
            ->openUrlInNewTab();
    }
}
