<?php

namespace App\Repositories;

use App\Enums\RoleEnum;
use App\Models\Event;
use Illuminate\Support\Collection;

class EventRepository
{
    public function getOwners(Event $event): Collection
    {
        return $event->users()
            ->whereHas('events', function ($query) use ($event) {
                $query->where('event_id', $event->id)
                    ->whereHas('role', function ($q) {
                        $q->where('name', RoleEnum::OWNER->value);
                    });
            })
            ->get();
    }
}
