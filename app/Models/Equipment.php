<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipment extends Model
{
    use HasFactory, HasUlids;

    public const UNITS = ['Nos', 'Sets', 'sqm'];

    protected $table = 'equipment';

    protected $fillable = [
        'category_id',
        'name',
        'capacity',
        'qty_own',
        'qty_hire',
        'unit',
        'condition',
        'sort_order',
    ];

    protected $attributes = [
        'qty_own'  => 0,
        'qty_hire' => 0,
        'unit'     => 'Nos',
    ];

    protected $casts = [
        'qty_own'    => 'integer',
        'qty_hire'   => 'integer',
        'sort_order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(EquipmentCategory::class, 'category_id');
    }
}
