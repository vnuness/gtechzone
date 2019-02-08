<?php
namespace App\Library;


use App\Models\Credentials\Permissions;

/**
 * Update acl permissions on BD
 *
 * Class PermissionBuilder
 * @package App\Library
 */
class PermissionBuilder
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
        $this->conf = config("permissions");
    }

    /**
     * Update all permissions
     */
    public function updateAllPermissions()
    {
        $this->updateTelasPermission();
        $this->updateSystemPermissions();
    }

    /**
     * Update permissions from telas key
     */
    public function updateTelasPermission()
    {
        $this->makePermissions($this->conf['telas']);
        $this->commitPermissions();
    }

    /**
     * Command to extract permissions from config recursively
     *
     * @param array $itens
     */
    private function makePermissions(array $itens)
    {
        foreach ($itens as $k => $v) {

            if (isset($v['permissions'])) {
                $this->extractPermission($v['permissions']);
            }

            if (isset($v['itens'])) {
                $this->makePermissions($v['itens']);
            }
        }
    }

    /**
     * Extract Permissions from file and put on array
     *
     * @param array $permissions
     */
    private function extractPermission(array $permissions)
    {
        foreach ($permissions as $k => $v) {
            $this->permissions[] = ['description' => $v, 'slug' => $k];
        }
    }

    /**
     * Update or create the permission on BD
     */
    private function commitPermissions()
    {
        foreach ($this->permissions as $v) {
            Permissions::updateOrCreate(
                ['slug' => $v['slug']],
                ['description' => $v['description']]
            );
        };
    }

    /**
     *  Update permissions from system key
     */
    public function updateSystemPermissions()
    {
        $this->makePermissions($this->conf['system']);
        $this->commitPermissions();

    }

}