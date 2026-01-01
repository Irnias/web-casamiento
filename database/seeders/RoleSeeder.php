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
        // 1. Crear Roles
        $ownerRole = Role::firstOrCreate(['name' => RoleEnum::OWNER->value], ['label' => 'Dueño']);
        $modRole = Role::firstOrCreate(['name' => RoleEnum::MODERATOR->value], ['label' => 'Moderador de Fotos']);
        $rsvpRole = Role::firstOrCreate(['name' => RoleEnum::RSVP_COORDINATOR->value], ['label' => 'Coordinador de RSVP']);

        // 2. Crear Permisos (Sincronizar todos del Enums)
        $permissions = [];
        foreach (PermissionEnum::cases() as $perm) {
            $permissions[$perm->value] = Permission::firstOrCreate(
                ['name' => $perm->value],
                ['label' => ucfirst(str_replace('_', ' ', $perm->value))]
            );
        }

        // 3. Asignar Permisos a Roles

        // A. DUEÑO (Todo)
        $ownerRole->permissions()->sync(Permission::all());

        // B. MODERADOR (Solo Fotos y Dashboard básico)
        $modRole->permissions()->sync([
            $permissions[PermissionEnum::VIEW_DASHBOARD->value]->id,
            $permissions[PermissionEnum::MANAGE_PHOTOS->value]->id,
            $permissions[PermissionEnum::VIEW_GUESTS->value]->id, // Puede ver quién viene, pero no tocar
        ]);

        // C. COORDINADOR RSVP (Solo Lista y Dashboard básico)
        $rsvpRole->permissions()->sync([
            $permissions[PermissionEnum::VIEW_DASHBOARD->value]->id,
            $permissions[PermissionEnum::VIEW_GUESTS->value]->id,
            $permissions[PermissionEnum::MANAGE_GUESTS->value]->id, // Crear tía Marta si faltaba
            $permissions[PermissionEnum::MANAGE_RSVP->value]->id,   // Confirmar asistencia manual
        ]);
    }
}
