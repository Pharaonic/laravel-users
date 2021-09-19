<?php

namespace Pharaonic\Laravel\Users\Traits;

use Pharaonic\Laravel\Users\Models\Role;
use Pharaonic\Laravel\Users\Models\Roleable;

/**
 * Auth Roles Trait.
 *
 * @author Moamen Eltouny (Raggi) <raggi@raggitech.com>
 */
trait HasRoles
{
    protected $rolesListArray;

    protected static function bootHasRoles()
    {
        // Deleting
        self::deleting(function ($model) {
            $model->roleables()->delete();
        });
    }

    /**
     * Get all of the main roleable objects.
     */
    public function roleables()
    {
        return $this->morphMany(Roleable::class, 'roleable');
    }

    /**
     * Get all attached roles to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function roles()
    {
        return $this->morphToMany(Role::class, 'roleable', 'roleables', 'roleable_id', 'role_id');
    }

    /**
     * Getting Permissoins List
     *
     * @return array
     */
    public function getRolesListAttribute()
    {
        if (is_array($this->rolesListArray)) return $this->rolesListArray;

        return $this->rolesListArray = $this->roles->mapWithKeys(function ($item) {
            return [$item['id'] => $item['code']];
        })->all();
    }

    /**
     * Prepare Roles IDs
     *
     * @param array $roles
     * @return array
     */
    private function prepareRolesIds($roles)
    {
        return Role::whereIn('code', $this->prepareRolesArray($roles))->get()->pluck('id')->toArray();
    }

    /**
     * Prepare Roles for checkers
     *
     * @param array $roles
     * @return array
     */
    private function prepareRolesArray($roles)
    {
        if (is_array($roles[0])) $roles = $roles[0];
        if (is_string($roles)) $roles = explode(',', $roles);

        return $roles;
    }

    /**
     * Give model roles
     *
     * @param string ...$roles
     * @return boolean
     */
    public function entrust(...$roles)
    {
        if (!empty(array_filter($this->roles()->sync($this->prepareRolesIds($roles), false)))) {
            $this->rolesListArray = null;
            return true;
        }

        return false;
    }

    /**
     * Checking if all roles allowed
     *
     * @param string $roles
     * @return boolean
     */
    public function entrusted(...$roles)
    {
        if (empty($roles)) return;

        $roles = $this->prepareRolesArray($roles);
        $rolesList = $this->rolesList;

        foreach ($roles as $role)
            if (!in_array($role, $rolesList))
                return false;

        return true;
    }

    /**
     * Checking if any of roles allowed
     *
     * @param string $roles
     * @return boolean
     */
    public function entrustedAny(...$roles)
    {
        if (empty($roles)) return;

        $roles = $this->prepareRolesArray($roles);
        $rolesList = $this->rolesList;

        foreach ($roles as $role)
            if (in_array($role, $rolesList))
                return true;

        return false;
    }

    /**
     * Forbid model Roles
     *
     * @param string ...$roles
     * @return boolean
     */
    public function distrust(...$roles)
    {
        if ($this->roles()->detach($this->prepareRolesIds($roles)) > 1) {
            $this->rolesListArray = null;
            return true;
        }

        return false;
    }

    /**
     * Checking if all roles disallowed
     *
     * @param string $roles
     * @return boolean
     */
    public function distrusted(...$roles)
    {
        if (empty($roles)) return;

        $roles = $this->prepareRolesArray($roles);
        $rolesList = $this->rolesList;

        foreach ($roles as $role)
            if (in_array($role, $rolesList))
                return false;

        return true;
    }

    /**
     * Checking if any of roles disallowed
     *
     * @param string $roles
     * @return boolean
     */
    public function distrustedAny(...$roles)
    {
        if (empty($roles)) return;

        $roles = $this->prepareRolesArray($roles);
        $rolesList = $this->rolesList;

        foreach ($roles as $role)
            if (!in_array($role, $rolesList))
                return true;

        return false;
    }

    /**
     * Sync the model Roles
     *
     * @param string ...$roles
     * @return boolean
     */
    public function syncRoles(...$roles)
    {
        if (!empty(array_filter($this->roles()->sync($this->prepareRolesIds($roles))))) {
            $this->rolesListArray = null;
            return true;
        }

        return false;
    }
}
