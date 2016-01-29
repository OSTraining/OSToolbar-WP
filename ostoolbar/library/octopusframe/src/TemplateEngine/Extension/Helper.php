<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\TemplateEngine\Extension;

use OctopusFrame\Factory;
use Twig_Extension;
use Twig_SimpleFunction;

defined('OCTOPUSFRAME_LOADED') or die();


class Helper extends Twig_Extension
{
    public function getName()
    {
        return 'helper';
    }

    public function getFunctions()
    {
        $class = get_called_class();

        return array(
            new Twig_SimpleFunction('route', $class . '::route')
        );
    }

    public static function route($params = array())
    {
        return Factory::getContainer()->router->route($params);
    }
}
