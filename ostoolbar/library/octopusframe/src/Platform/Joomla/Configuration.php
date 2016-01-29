<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Platform\Joomla;

use Alledia\Framework\Joomla\Extension\Licensed as LicensedExtension;
use OctopusFrame\Registry\ArrayRegistry;
use OctopusFrame\Registry\RegistrableInterface;

defined('OCTOPUSFRAME_LOADED') or die();


class Configuration extends ArrayRegistry
{
    protected $extensionNameSpace = 'OctopusFrame';

    /**
     * Constructor
     *
     * @param mixed                $storage The parameters storage
     * @param RegistrableInterface $options The options
     */
    public function __construct($storage = null, RegistrableInterface $options = null)
    {
        $extension = new LicensedExtension($this->extensionNameSpace, 'component');

        // Load the current options from the database
        $this->storage = $extension->params;

        // Check if we need to set any configuration
        if (is_array($storage)) {
            if (!empty($storage)) {
                foreach ($storage as $key => $value) {
                    parent::set($key, $value);
                }
            } else {
                $this->storage = array();
            }

            $this->update();
        }
    }
}
