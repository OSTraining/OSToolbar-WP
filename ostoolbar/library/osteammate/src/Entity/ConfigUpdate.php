<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\Entity;

defined('OSTEAMMATE_LOADED') or die();

use stdClass;


/**
 * Class for the Config Update
 */
class ConfigUpdate extends AbstractEntity
{
    /**
     * The plugin's group title
     *
     * @var string
     */
    public $groupTitle = null;

    /**
     * The plugin's title
     *
     * @var string
     */
    public $title = null;

    /**
     * The base URL for icons
     *
     * @var string
     */
    public $iconBaseUrl = null;

    /**
     * The custom layout
     *
     * @var string
     */
    public $customLayout = null;
}
