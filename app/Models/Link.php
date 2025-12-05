<?php

namespace App\Models;

use App\Enums\Icon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'atlas_id',
        'name',
        'url',
        'icon',
        'description',
        'order',
        'is_active',
    ];

    public function atlas(): BelongsTo
    {
        return $this->belongsTo(Atlas::class);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order' => 'integer',
            'icon' => Icon::class,
        ];
    }
}
