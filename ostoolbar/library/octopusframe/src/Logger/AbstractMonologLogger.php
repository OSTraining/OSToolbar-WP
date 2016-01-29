<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Logger;

use Monolog\Logger as BaseLogger;
use Monolog\Handler\StreamHandler;
use OctopusFrame\Registry\ArrayRegistry;

defined('OCTOPUSFRAME_LOADED') or die();


abstract class AbstractMonologLogger extends BaseLogger
{
    public function __construct(ArrayRegistry $options)
    {
        $basePath = $options->get('path');
        $name     = $options->get('name');

        $handlers = array(
            new StreamHandler("{$basePath}/{$name}.log", static::DEBUG)
        );

        parent::__construct($name, $handlers);
    }
}
