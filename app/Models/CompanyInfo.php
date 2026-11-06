<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Single row of site-wide company text. Read it with CompanyInfo::current().
 */
class CompanyInfo extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'company_info';

    protected $fillable = [
        'name',
        'tagline',
        'intro',
        'vision',
        'mission',
        'founder_message',
        'email',
        'phone',
        'logo_url',
    ];

    public static function current(): ?self
    {
        return static::query()->first();
    }

    /** Mission is stored one point per line. */
    public function missionPoints(): array
    {
        return array_values(array_filter(array_map('trim', explode("\n", (string) $this->mission))));
    }
}
