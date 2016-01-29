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
 * Class for the update checksum set
 */
class UpdateChecksum extends AbstractEntity
{
    /**
     * The checksum for config
     *
     * @var string
     */
    public $config =  null;

    /**
     * The checksum for pathways
     *
     * @var string
     */
    public $pathways =  null;

    /**
     * The checksum for templates
     *
     * @var string
     */
    public $templates =  null;

    /**
     * The checksum for the package
     *
     * @var string
     */
    public $package =  null;
}
