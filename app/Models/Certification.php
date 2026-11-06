<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'title',
        'issuer',
        'number',
        'issued_on',
        'expires_on',
        'file_url',
        'is_public',
        'sort_order',
    ];

    protected $attributes = [
        'is_public' => false,
    ];

    protected $casts = [
        'issued_on'  => 'date',
        'expires_on' => 'date',
        'is_public'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }
}
