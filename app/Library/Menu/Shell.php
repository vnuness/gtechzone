<?php
/**
 * Created by PhpStorm.
 * User: MeetaWeb
 * Date: 07/05/2018
 * Time: 10:51
 */

namespace App\Library\Menu;

/**
 * Class Shell
 * @package App\Library\Menu
 */
class Shell implements MenuInterface
{
    /**
     * @var array
     */
    private $options = [];

    /**
     * @var string
     */
    private $menu = "";

    /**
     * @param MenuInterface $option
     */
    public function add(MenuInterface $option)
    {
        $this->options[] = $option;
    }


    /**
     * @return string
     */
    public function dump()
    {
        $this->menu = "<ul>";

        foreach ($this->options as $item) {
            $this->menu .= $item->dump();
        }

        $this->menu .= "<ul>";

        return $this->menu;
    }
}