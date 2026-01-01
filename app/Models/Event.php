<?php

namespace App\Models;

use App\Enums\RoleEnum;
use App\Exceptions\FeatureNotActiveException;
use App\Exceptions\SettingNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(EventSetting::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function owners(): BelongsToMany
    {
        $ownerRoleId = Role::where('name', RoleEnum::OWNER->value)->first()?->id;
        return $this->users()->wherePivot('role_id', $ownerRoleId);
    }

    /**
     * @throws SettingNotFoundException
     */
    public function getSetting(string $key): string
    {
        $setting = $this->settings->firstWhere('key', $key);

        if ($setting === null) {
            throw SettingNotFoundException::forEventAndKey($this->name, $key);
        }
        return (string) $setting->value;
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'event_feature')
            ->withTimestamps();
    }


    public function hasFeature(string $key): bool
    {
        return $this->features->contains('key', $key);
    }

    /**
     * @throws FeatureNotActiveException
     */
    public function requireFeature(string $key): void
    {
        if (! $this->hasFeature($key)) {
            throw FeatureNotActiveException::forKey($key);
        }
    }
}
