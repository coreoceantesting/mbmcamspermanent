<?php

namespace App\Models\Traits;

use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

trait RoleTrait{

    public function assignRole(...$roles)
    {
        $roles = collect($roles)
            ->flatten()
            ->reduce(function ($array, $role) {
                if (empty($role)) {
                    return $array;
                }

                $role = $this->getStoredRole($role);
                if (! $role instanceof Role) {
                    return $array;
                }

                $this->ensureModelSharesGuard($role);

                $array[$role->getKey()] = PermissionRegistrar::$teams && ! is_a($this, Permission::class) ?
                    [PermissionRegistrar::$teamsKey => getPermissionsTeamId()] : [];

                return $array;
            }, []);

        $model = $this->getModel();

        if ($model->exists) {
            $this->roles()->sync($roles, false);
            $model->load('roles');
        } else {
            $class = \get_class($model);

            $class::saved(
                function ($object) use ($roles, $model) {
                    if ($model->getKey() != $object->getKey()) {
                        return;
                    }
                    $model->roles()->sync($roles, false);
                    $model->load('roles');
                }
            );
        }

        if (is_a($this, get_class($this->getPermissionClass()))) {
            $this->forgetCachedPermissions();
        }

        return $this;
    }



}
