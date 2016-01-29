<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Router;

defined('OCTOPUSFRAME_LOADED') or die();


interface RoutableInterface
{
    public function getViewName();

    public function getParamsList($onlyPrefixed = true);

    public function setParam($param, $value, $prefixed = true);

    public function getParam($param, $default = null, $prefixed = true);
}
