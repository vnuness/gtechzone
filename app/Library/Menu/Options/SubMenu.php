<?php
/**
 * Created by PhpStorm.
 * User: MeetaWeb
 * Date: 07/05/2018
 * Time: 10:51
 */

namespace App\Library\Menu\Options;

use App\Library\Menu\MenuInterface;

/**
 * Class SubMenu
 * @package App\Library\Menu\Options
 */
class SubMenu implements MenuInterface
{
    /**
     * @var array
     */
    private $itens = [];

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $icon_class;

    /**
     * SubMenu constructor.
     * @param string $label
     * @param string $icon_class
     */
    public function __construct(string $label = '', string $icon_class = '')
    {
        $this->label = $label;
        $this->icon_class = $icon_class;
    }

    /**
     * @param MenuInterface $option
     */
    public function add(MenuInterface $option)
    {
        $this->itens[] = $option;
    }


    /**
     * @return string
     */
    public function dump() : string
    {
        $icon = empty($this->icon_class) ? '' : "<i class=\"$this->icon_class\"></i>";

        $ul = '<li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect waves-primary">
                        ' . $icon . '<span>' . $this->label . '</span><span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">';

        foreach ($this->itens as $item) {
            $ul .= $item->dump();
        }

        $ul .= '</ul></li>';

        return $ul;
    }
}