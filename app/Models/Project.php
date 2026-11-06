<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory, HasUlids;

    public const STATUSES = ['ongoing', 'completed'];

    protected $fillable = [
        'client_id',
        'slug',
        'title',
        'location',
        'start_date',
        'end_date',
        'status',
        'summary',
        'scope',
        'contract_value',
        'show_value',
        'is_featured',
        'is_published',
    ];

    protected $attributes = [
        'status'       => 'completed',
        'show_value'   => false,
        'is_featured'  => false,
        'is_published' => false,
    ];

    protected $casts = [
        'start_date'     => 'date',
        'end_date'       => 'date',
        'contract_value' => 'decimal:2',
        'show_value'     => 'boolean',
        'is_featured'    => 'boolean',
        'is_published'   => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * Newest first, as on the Projects page: by the year the work ended (or started,
     * when there is no end date), so a 2017–2024 job sorts as 2024.
     */
    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByRaw('COALESCE(end_date, start_date) DESC NULLS LAST')->orderBy('title');
    }
}
