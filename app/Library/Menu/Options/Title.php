<?php
/**
 * Created by PhpStorm.
 * User: MeetaWeb
 * Date: 07/05/2018
 * Time: 21:15
 */

namespace App\Library\Menu\Options;

use App\Library\Menu\MenuInterface;

/**
 * Class Title
 * @package App\Library\Menu\Options
 */
class Title implements MenuInterface
{
    /**
     * @var string
     */
    private $label;

    /**
     * Title constructor.
     * @param string $label
     */
    public function __construct(string $label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function dump()
    {
        return "<li class=\"menu-title\">{$this->label}</li>";
    }

}