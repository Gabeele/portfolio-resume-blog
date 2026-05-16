<?php

namespace App\Events;

use App\Models\Resume;
use Illuminate\Foundation\Events\Dispatchable;

class ResumeInfoChangeEvent
{
    use Dispatchable;

    public function __construct(public Resume $resume)
    {
        logger()->info('Resume info changed');
    }
}
