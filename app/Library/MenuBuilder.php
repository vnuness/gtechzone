<?php
/**
 * Created by PhpStorm.
 * User: MeetaWeb
 * Date: 07/05/2018
 * Time: 09:54
 */

namespace App\Library;

use App\Library\Menu\Options\Link;
use App\Library\Menu\Options\SubMenu;
use App\Library\Menu\Options\Title;
use App\Library\Menu\Shell;

/**
 * Class MenuBuilder
 * @package App\Library
 */
class MenuBuilder
{
    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    private $conf;

    /**
     * @var
     */
    private $telas;

    /**
     * MenuBuilder constructor.
     */
    public function __construct()
    {
        $this->conf = config("permissions.telas");
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->build();
    }

    /**
     * @return string
     */
    public function build()
    {
        $menu_shell = new Shell();

        try {
            $this->extract($menu_shell, $this->conf);
        } catch (\Exception $e) {
            report($e);
            echo "<ul><li class='menu-title'>
                            <span class='badge badge-danger pull-left'><i class='fa fa-warning'></i></span>
                            &nbsp;Falha ao Renderizar o Menu.
                  </li></ul>";
        }

        return $menu_shell->dump();
    }

    /**
     * @param $menu
     * @param array $itens
     * @return mixed
     */
    private function extract($menu, array $itens)
    {
        foreach ($itens as $k => $v) {

            if ($this->checkPermissionRecursive($v)) {
                $menu->add($this->getItem($k, $v));
            }

            if (isset($v['itens']) && $v['menu']['type'] != "sub-menu") {
                $this->extract($menu, $v['itens']);
            }
        }

        return $menu;
    }

    private function checkPermissionRecursive($node)
    {
        if (isset($node['permissions'])) {
            foreach ($node['permissions'] as $k => $v) {
                if (auth()->user()->can($k)) {
                    return true;
                }
            }

            return false;
        }

        if (isset($node['menu']) && $node['menu']['type'] == 'link') {
            return true;
        }

        if (isset($node['itens'])) {
            return $this->checkPermissionRecursive($node['itens']);
        }

        if (is_array($node)) {
            foreach ($node as $k => $v) {
                if ($this->checkPermissionRecursive($v)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function getItem($label, $itens)
    {
        switch ($itens['menu']['type']) {
            case "link":
                $item = new Link(route($itens['menu']['route']), $label, $itens['menu']['icon-class']??'');
                break;
            case "title":
                $item = new Title($label);
                break;
            case "sub-menu":
                $item = $this->extract(new SubMenu($label, $itens['menu']['icon-class']??''), $itens['itens']);
                break;
            default:
                throw new \Exception("Undefined menu type item");
                break;
        }

        return $item;
    }
}