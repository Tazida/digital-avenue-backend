<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    use HasFactory, HasUlids;

    public const STATUSES = ['new', 'read', 'replied'];

    protected $fillable = [
        'service_id',
        'name',
        'email',
        'phone',
        'company',
        'message',
        'status',
    ];

    protected $attributes = [
        'status' => 'new',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
