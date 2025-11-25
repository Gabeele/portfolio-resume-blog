<?php

namespace App\Models;

use App\Models\Scopes\CurrentUserScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ScopedBy([CurrentUserScope::class])]
class Certificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'file',
        'url',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
