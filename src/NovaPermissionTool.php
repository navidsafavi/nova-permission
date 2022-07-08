<?php

namespace Vyuldashev\NovaPermission;

use Gate;
use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Tool;

class NovaPermissionTool extends Tool
{
    public string $roleResource = Role::class;

    public string $permissionResource = Permission::class;

    public string $rolePolicy = RolePolicy::class;

    public string $permissionPolicy = PermissionPolicy::class;

    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Gate::policy(config('permission.models.permission'), $this->permissionPolicy);
        Gate::policy(config('permission.models.role'), $this->rolePolicy);
    }

    public function roleResource(string $roleResource): NovaPermissionTool
    {
        $this->roleResource = $roleResource;

        return $this;
    }

    public function permissionResource(string $permissionResource): NovaPermissionTool
    {
        $this->permissionResource = $permissionResource;

        return $this;
    }

    public function rolePolicy(string $rolePolicy): NovaPermissionTool
    {
        $this->rolePolicy = $rolePolicy;

        return $this;
    }

    public function permissionPolicy(string $permissionPolicy)
    {
        $this->permissionPolicy = $permissionPolicy;

        return $this;
    }

    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function menu(Request $request)
    {
        return [

            MenuSection::make(__('nova-permission-tool::navigation.sidebar-label'), [
                MenuItem::resource($this->roleResource),
                MenuItem::resource($this->permissionResource),
            ])->icon('key')->collapsable(),

        ];
    }
}
