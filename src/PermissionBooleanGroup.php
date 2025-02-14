<?php

namespace Vyuldashev\NovaPermission;

use Illuminate\Support\Collection;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\Permission\Models\Permission as PermissionModel;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasPermissions;

class PermissionBooleanGroup extends BaseBooleanGroup
{
    protected null|string $titleKey = null;

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  null  $attribute
     * @param  callable|null  $resolveCallback
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct(
            $name,
            $attribute,
            $resolveCallback ?? static function (Collection $permissions) {
                return $permissions->mapWithKeys(function (PermissionModel $permission) {
                    return [$permission->name => true];
                });
            }
        );

        $this->classModel = app(PermissionRegistrar::class)->getPermissionClass();

        $options = $this->classModel::get()->pluck('name', 'name')->toArray();
        $this->options($options);
    }

    /**
     * @param  NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  HasPermissions  $model
     * @param  string  $attribute
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if (! $request->exists($requestAttribute)) {
            return;
        }

        $values = collect(json_decode($request[$requestAttribute], true))
            ->filter(static function (bool $value) {
                return $value;
            })
            ->keys()
            ->toArray();

        $model->syncPermissions($values);
    }
}
