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
 * Class Link
 * @package App\Library\Menu\Options
 */
class Link implements MenuInterface
{
    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $icon;

    /**
     * Link constructor.
     * @param string $url
     * @param string $label
     * @param string|null $icon_class
     */
    public function __construct(string $url, string $label, string $icon_class = null)
    {
        $this->label = $label;
        $this->url = $url;
        $this->icon = $icon_class;
    }

    /**
     * @return string
     */
    public function dump()
    {
        $icon = empty($this->icon) ? '' : "<i class=\"{$this->icon}\"></i>";

        return "<li>
                    <a href=\"{$this->url}\" class=\"waves-effect waves-primary\">
                       {$icon}<span>{$this->label} </span>
                    </a>
                </li>";

    }

}