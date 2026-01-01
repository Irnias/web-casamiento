<?php

namespace App\Models;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_user')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function hasPermission(PermissionEnum $permission, Event $event): bool
    {
        $targetEvent = $this->events->find($event->id);

        if (! $targetEvent) {
            return false;
        }

        $roleId = $targetEvent->pivot->role_id;
        $role = Role::with('permissions')->find($roleId);

        if (! $role) return false;

        if ($role->name === RoleEnum::SUPER_ADMIN->value) return true;

        return $role->permissions->contains('name', $permission->value);
    }
}
