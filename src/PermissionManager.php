<?php

namespace Newnet\Acl;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Newnet\Acl\Contracts\PermissionManagerInterface;

class PermissionManager implements PermissionManagerInterface
{
    /**
     * All Permission
     * @var array
     */
    protected $permissions = [];

    /**
     * All Permission in tree list
     * @var array
     */
    protected $treePermissions = [];

    /**
     * Get all Permission.
     * @return array
     */
    public function all()
    {
        return $this->permissions;
    }

    public function allTree()
    {
        return Arr::sort($this->treePermissions);
    }

    /**
     * Get all Permission Without Key.
     * @return array
     */
    public function allTreeWithoutKey()
    {
        return $this->removeChildrenKey($this->allTree());
    }

    public function add($key, $label)
    {
        $originKey = $key;

        $key = Str::replaceFirst('.admin.', '.', $key);
        $this->permissions[$originKey] = $label;

        $this->setDefaultGroupLabel($key);

        $arrKey = str_replace('.', '.children.', $key);
        Arr::set($this->treePermissions, $arrKey, [
            'key'   => $originKey,
            'label' => $label,
        ]);

        return $this;
    }

    public function setGroupLabel($key, $label)
    {
        $groupLabelKey = str_replace('.', '.children.', $key) . '.label';

        Arr::set($this->treePermissions, $groupLabelKey, $label);

        return $this;
    }

    private function removeChildrenKey($array)
    {
        $newData = [];

        foreach ($array as $key => $item) {
            if (!empty($item['children'])) {
                $item['children'] = $this->removeChildrenKey($item['children']);
            }

            $newData[] = $item;
        }

        return $newData;
    }

    private function setDefaultGroupLabel($key)
    {
        $segments = explode('.', $key);

        foreach ($segments as $segment) {
            array_pop($segments);

            if (empty($segments)) {
                continue;
            }

            $groupKey = implode('.', $segments);
            $groupLabelKey = str_replace('.', '.children.', $groupKey) . '.label';

            if (!Arr::has($this->treePermissions, $groupLabelKey)) {
                Arr::set($this->treePermissions, $groupLabelKey, ucfirst(last($segments)));
            }
        }
    }
}
