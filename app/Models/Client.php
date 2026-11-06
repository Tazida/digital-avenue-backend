<?php

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    /** @use HasFactory<ClientFactory> */
    use HasFactory, HasSortOrder, HasUlids;

    public const TYPES = ['un_agency', 'government', 'private'];

    protected $fillable = [
        'name',
        'type',
        'logo_url',
        'show_logo',
        'website',
        'sort_order',
    ];

    protected $attributes = [
        'show_logo'  => false,
        'sort_order' => 0,
    ];

    protected $casts = [
        'show_logo'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
