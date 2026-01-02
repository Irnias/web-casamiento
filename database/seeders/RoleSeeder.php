<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $ownerRole = Role::firstOrCreate(['name' => RoleEnum::OWNER->value], ['label' => 'Dueño del Evento']);
        $modRole = Role::firstOrCreate(['name' => RoleEnum::MODERATOR->value], ['label' => 'Moderador']);
        $rsvpRole = Role::firstOrCreate(['name' => RoleEnum::RSVP_COORDINATOR->value], ['label' => 'Coordinador RSVP']);

        $permissions = [];
        foreach (PermissionEnum::cases() as $perm) {
            $permissions[$perm->value] = Permission::firstOrCreate(
                ['name' => $perm->value],
                ['label' => ucfirst(str_replace('_', ' ', $perm->value))]
            );
        }

        $ownerRole->permissions()->sync(Permission::all());

        $modRole->permissions()->sync([
            $permissions[PermissionEnum::VIEW_DASHBOARD->value]->id,
            $permissions[PermissionEnum::MANAGE_PHOTOS->value]->id,
            $permissions[PermissionEnum::VIEW_GUESTS->value]->id,
        ]);

        $rsvpRole->permissions()->sync([
            $permissions[PermissionEnum::VIEW_DASHBOARD->value]->id,
            $permissions[PermissionEnum::MANAGE_GUESTS->value]->id,
            $permissions[PermissionEnum::MANAGE_RSVP->value]->id,
            $permissions[PermissionEnum::VIEW_GUESTS->value]->id,
        ]);
    }
}
