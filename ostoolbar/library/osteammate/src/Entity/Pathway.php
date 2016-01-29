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
 * Class for the Pathway
 */
class Pathway extends AbstractEntity
{
    /**
     * The ID of this pathway
     *
     * @var int
     */
    public $id = 0;

    /**
     * The title of this pathway
     *
     * @var string
     */
    public $title = null;

    /**
     * The alias of this pathway
     *
     * @var string
     */
    public $alias = null;

    /**
     * The description of this pathway
     *
     * @var string
     */
    public $description = null;

    /**
     * The intro text of this pathway
     *
     * @var string
     */
    public $introtext = null;

    /**
     * The relative path for the picture of this pathway
     *
     * @var string
     */
    public $picture = null;

    /**
     * The relative path for the icon of this pathway
     *
     * @var string
     */
    public $icon = null;

    /**
     * The publish state of this pathway
     *
     * @var boolean
     */
    public $published = true;

    /**
     * The ordering of this pathway
     *
     * @var integer
     */
    public $ordering = 0;
}
