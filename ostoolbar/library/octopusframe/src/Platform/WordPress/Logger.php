<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Platform\WordPress;

use OctopusFrame\Logger\AbstractMonologLogger as BaseLogger;
use OctopusFrame\Registry\ArrayRegistry;

defined('OCTOPUSFRAME_LOADED') or die();


class Logger extends BaseLogger
{
    public function __construct(ArrayRegistry $options)
    {
        if (!isset($options)) {
            $options = new ArrayRegistry;
        }
        $options->set('path', OSTOOLBAR_LOG_PATH);

        parent::__construct($options);
    }
}
