<?php

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory, HasSortOrder, HasUlids;

    protected $fillable = [
        'label',
        'address',
        'phone',
        'map_url',
        'is_head_office',
        'sort_order',
    ];

    protected $attributes = [
        'is_head_office' => false,
    ];

    protected $casts = [
        'is_head_office' => 'boolean',
        'sort_order'     => 'integer',
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('is_head_office')->orderBy('sort_order');
    }
}
