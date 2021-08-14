<?php

namespace Pharaonic\Laravel\Users\Traits;

use Pharaonic\Laravel\Users\Models\Permission;
use Pharaonic\Laravel\Users\Traits\HasRoles;

trait HasPermissions
{
    /**
     * Get all attached permissions to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function permissions()
    {
        return $this->morphToMany(Permission::class, 'permissible', 'permissibles', 'permissible_id', 'permission_id');
    }

    /**
     * Getting Permissoins List
     *
     * @return array
     */
    public function getPermissionsListAttribute()
    {
        $permissions = $this->permissions->map(function ($item) {
            return $item['code'];
        })->all();

        // Append Roles Permissions IF Found
        if (!in_array(HasRoles::class, class_uses($this))) return $permissions;

        foreach ($this->roles()->with('permissions')->get() as $role)
            $permissions = array_merge($permissions, $role->permissionsList);

        return $permissions;
    }

    /**
     * Prepare Permissions IDs
     *
     * @param array $permissions
     * @return array
     */
    private function preparePermissionsIds(array $permissions)
    {
        return Permission::whereIn('code', $this->preparePermissionsArray($permissions))->get()->pluck('id')->toArray();
    }

    /**
     * Prepare Permissions for checkers
     *
     * @param array $permissions
     * @return array
     */
    private function preparePermissionsArray($permissions)
    {
        if (is_array($permissions[0])) $permissions = $permissions[0];
        if (is_string($permissions)) $permissions = explode(',', $permissions);

        return $permissions;
    }

    /**
     * Give model permissions
     *
     * @param string ...$permissions
     * @return boolean
     */
    public function permit(...$permissions)
    {
        return !empty(array_filter($this->permissions()->sync($this->preparePermissionsIds($permissions), false)));
    }

    /**
     * Checking if all permissions allowed
     *
     * @param string $permissions
     * @return boolean
     */
    public function permitted(...$permissions)
    {
        if (empty($permissions)) return;

        $permissions = $this->preparePermissionsArray($permissions);
        $permissionsList = $this->permissionsList;

        foreach ($permissions as $permission)
            if (!in_array($permission, $permissionsList))
                return false;

        return true;
    }

    /**
     * Checking if any of permissions allowed
     *
     * @param string $permissions
     * @return boolean
     */
    public function permittedAny(...$permissions)
    {
        if (empty($permissions)) return;

        $permissions = $this->preparePermissionsArray($permissions);
        $permissionsList = $this->permissionsList;

        foreach ($permissions as $permission)
            if (in_array($permission, $permissionsList))
                return true;

        return false;
    }

    /**
     * Forbid model Permissions
     *
     * @param string ...$permissions
     * @return boolean
     */
    public function forbid(...$permissions)
    {
        return $this->permissions()->detach($this->preparePermissionsIds($permissions)) > 0;
    }

    /**
     * Checking if all permissions disallowed
     *
     * @param string $permissions
     * @return boolean
     */
    public function forbad(...$permissions)
    {
        if (empty($permissions)) return;

        $permissions = $this->preparePermissionsArray($permissions);
        $permissionsList = $this->permissionsList;

        foreach ($permissions as $permission)
            if (in_array($permission, $permissionsList))
                return false;

        return true;
    }

    /**
     * Checking if any of permissions disallowed
     *
     * @param string $permissions
     * @return boolean
     */
    public function forbadAny(...$permissions)
    {
        if (empty($permissions)) return;

        $permissions = $this->preparePermissionsArray($permissions);
        $permissionsList = $this->permissionsList;

        foreach ($permissions as $permission)
            if (!in_array($permission, $permissionsList))
                return true;

        return false;
    }

    /**
     * Sync the model Permissions
     *
     * @param string ...$permissions
     * @return boolean
     */
    public function syncPermissions(...$permissions)
    {
        $result = $this->permissions()->sync($this->preparePermissionsIds($permissions));

        return !empty(array_filter($result));
    }
}
