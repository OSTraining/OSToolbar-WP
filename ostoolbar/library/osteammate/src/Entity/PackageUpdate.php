<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\Entity;

defined('OSTEAMMATE_LOADED') or die();


/**
 * Class for the Pathway Update
 */
class PackageUpdate extends AbstractEntity
{
    /**
     * The package version
     *
     * @var string
     */
    public $version = '0.0.0';

    /**
     * The url to download the package
     *
     * @var string
     */
    public $url = null;
}
