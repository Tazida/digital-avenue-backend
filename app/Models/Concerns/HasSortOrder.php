<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;

/**
 * New rows go to the end of the list; the admin panel reorders them by drag and drop.
 */
trait HasSortOrder
{
    protected static function bootHasSortOrder(): void
    {
        static::creating(function (Model $model) {
            if (! $model->sort_order) {
                $model->sort_order = (int) static::query()->max('sort_order') + 1;
            }
        });
    }
}
