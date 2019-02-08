<?php
namespace App\Library;

use App\Models\Credentials\Permissions;
use App\Models\Credentials\Roles;

/**
 * Update acl permissions on BD
 *
 * Class PermissionBuilder
 * @package App\Library
 */
class ProfileTreeBuilder
{
    /**
     * File with acl permissions
     * @var \Illuminate\Config\Repository|mixed
     */
    private $conf;

    /**
     * Extracted permissions
     * @var
     */
    private $permissions;


    /**
     * PermissionBuilder constructor.
     */
    public function __construct()
    {
        $this->conf = config("permissions.telas");

        $this->extractNodes($this->conf);
    }


    /**
     * @return string
     */
    public function getAsJson()
    {
        return json_encode($this->getArray());
    }


    /**
     * @return string
     */
    public function getAsArray()
    {
        return $this->permissions;
    }


    /**
     * @param Roles $role
     */
    public function checkInProfile(Roles $role)
    {
        foreach ($this->permissions as &$v) {
            if (is_numeric($v['id'])) {
                $v['state']['selected'] = $role->hasPermissionId($v['id']) !== false;
            }
        }

        return $this;
    }


    /**
     *
     */
    public function disableAll()
    {
        foreach ($this->permissions as &$v) {
            $v['state']['disabled'] = true;
        }

        return $this;
    }


    public function closeAll()
    {
        foreach ($this->permissions as &$v) {
            $v['state']['opened'] = false;
        }

        return $this;
    }


    public function openAll()
    {
        foreach ($this->permissions as &$v) {
            $v['state']['opened'] = true;
        }

        return $this;
    }


    /**
     * @param $node
     * @param string $parent_id
     */
    private function extractNodes($node, $parent_id = '#')
    {
        foreach ($node as $k => $v) {

            $current_id = snake_case($parent_id . $k);

            if (isset($v['menu'])) {
                $icon = $this->getIcon($v['menu']);
                $this->addItem($parent_id, $current_id, $k, $icon);
            }

            if (isset($v['itens'])) {
                $this->extractNodes($v['itens'], $current_id);
            }

            if (isset($v['permissions'])) {
                $this->addPermissions($v['permissions'], $current_id);
            }
        }
    }

    /**
     * @param $parent_id
     * @param $id
     * @param $text
     * @param string $icon
     */
    private function addItem($parent_id, $id, $text, $icon = '')
    {
        $this->permissions[] = [
            'id' => $id,
            'parent' => $parent_id,
            'text' => $text,
            'icon' => $icon
        ];
    }

    /**
     * @param array $permissions
     * @param $parent_id
     */
    private function addPermissions(array $permissions, $parent_id)
    {
        foreach ($permissions as $k => $v) {
            $permission = Permissions::where('slug', $k)->firstOrFail();
            $this->addItem($parent_id, $permission->id, $v, 'mdi mdi-key');
        }
    }

    /**
     * @param $menu
     * @return string
     */
    private function getIcon($menu)
    {
        if (isset($menu['icon-class'])) {
            return $menu['icon-class'];
        }

        switch ($menu['type']) {
            case 'title':
                $icon = 'mdi mdi-format-list-bulleted';
                break;
            case 'link':
                $icon = 'ion-monitor';
                break;
            default:
                $icon = '';
                break;
        }

        return $icon;
    }

}