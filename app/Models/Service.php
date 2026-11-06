<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory, HasUlids;

    public const CATEGORIES = [
        'dredging_land'         => 'Dredging and land development',
        'civil_infrastructure'  => 'Civil and infrastructure',
        'mechanical_electrical' => 'Mechanical and electrical',
        'machinery_supply'      => 'Machinery hire and supply',
    ];

    protected $fillable = [
        'slug',
        'title',
        'category',
        'summary',
        'description',
        'icon',
        'sort_order',
        'is_published',
    ];

    protected $attributes = [
        'sort_order'   => 0,
        'is_published' => false,
    ];

    protected $casts = [
        'sort_order'   => 'integer',
        'is_published' => 'boolean',
    ];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }
}
