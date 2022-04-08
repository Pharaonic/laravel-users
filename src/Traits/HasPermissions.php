<?php

namespace Pharaonic\Laravel\Users\Traits;

use Pharaonic\Laravel\Users\Models\Permissions\Permissible;
use Pharaonic\Laravel\Users\Models\Permissions\Permission;
use Pharaonic\Laravel\Users\Models\Roles\Role;
use Pharaonic\Laravel\Users\Traits\HasRoles;

/**
 * Auth Permissions Trait.
 *
 * @author Moamen Eltouny (Raggi) <support@raggitech.com>
 */
trait HasPermissions
{
    protected $permissionsListArray;

    protected static function bootHasPermissions()
    {
        // Deleting
        self::deleting(function ($model) {
            $model->permissibles()->delete();
        });
    }

    /**
     * Get all of the main permissibles objects.
     */
    public function permissibles()
    {
        return $this->morphMany(Permissible::class, 'permissible');
    }

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
        if (is_array($this->permissionsListArray)) return $this->permissionsListArray;

        $permissions = $this->permissions()->pluck('permissions.code')->toArray();

        // Append Roles Permissions IF Found
        if (!in_array(HasRoles::class, class_uses($this))) return $permissions;

        $permissions = array_merge(
            $permissions,
            Permissible::where('permissible_type', Role::class)
                ->whereIn('permissible_id', $this->roles()->pluck('roles.id')->toArray())
                ->join('permissions', 'permissions.id', '=', 'permissibles.permission_id')
                ->pluck('permissions.code')->toArray()
        );

        return $this->permissionsListArray = $permissions;
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
        if (!empty(array_filter($this->permissions()->sync($this->preparePermissionsIds($permissions), false)))) {
            $this->permissionsListArray = null;
            return true;
        }

        return false;
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
        if ($this->permissions()->detach($this->preparePermissionsIds($permissions)) > 0) {
            $this->permissionsListArray = null;
            return true;
        }

        return false;
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
        if (!empty(array_filter($this->permissions()->sync($this->preparePermissionsIds($permissions))))) {
            $this->permissionsListArray = null;
            return true;
        }

        return false;
    }
}
