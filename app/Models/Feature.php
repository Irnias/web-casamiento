<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends Model
{
    protected $guarded = [];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_feature');
    }
}
