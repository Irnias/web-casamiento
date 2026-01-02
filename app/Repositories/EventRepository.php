<?php

namespace App\Repositories;

use App\Enums\RoleEnum;
use App\Models\Event;
use App\Models\Role;
use Illuminate\Support\Collection;

class EventRepository
{
    public function getOwners(Event $event): Collection
    {

        $ownerRoleId = Role::where('name', RoleEnum::OWNER->value)->value('id');

        return $event->users()
            ->wherePivot('role_id', $ownerRoleId)
            ->get();
    }

}
