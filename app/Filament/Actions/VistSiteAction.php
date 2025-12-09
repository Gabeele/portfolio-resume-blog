<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;

class VistSiteAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->name('Visit');

        $this->label('Visit Site');

        auth()->user()->siteUrl();
        $this->url(fn() => auth()->user()->siteUrl())
            ->openUrlInNewTab();
    }
}
