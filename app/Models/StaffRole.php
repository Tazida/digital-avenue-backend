<?php

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffRole extends Model
{
    use HasFactory, HasSortOrder, HasUlids;

    protected $fillable = [
        'title',
        'count_own',
        'count_hire',
        'sort_order',
    ];

    protected $attributes = [
        'count_own'  => 0,
        'count_hire' => 0,
    ];

    protected $casts = [
        'count_own'  => 'integer',
        'count_hire' => 'integer',
        'sort_order' => 'integer',
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }
}
