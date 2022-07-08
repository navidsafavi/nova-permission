<?php

namespace Vyuldashev\NovaPermission;

use Laravel\Nova\Fields\BooleanGroup;

class BaseBooleanGroup extends BooleanGroup
{
    protected $classModel = null;

    /**
     * @param  string  $key
     * @return $this
     */
    public function setTitleKey(string $key): static
    {
        $options = $this->classModel::get()->pluck($key ?? 'name', 'name')->toArray();

        $this->options($options);

        return $this;
    }
}
