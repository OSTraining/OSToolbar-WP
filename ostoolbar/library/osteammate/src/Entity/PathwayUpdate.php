<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\Entity;

use stdClass;

defined('OSTEAMMATE_LOADED') or die();


/**
 * Class for the Pathway Update
 */
class PathwayUpdate extends AbstractEntity
{
    /**
     * A list of pathways the leader has access
     *
     * @var array
     */
    public $pathways = array();

    /**
     * A list of all pathways
     *
     * @var array
     */
    public $allPathways = array();

    /**
     * Default constructor. It can set the internal attributes based on a
     * stdClass object result of a JSON decode procedure.
     *
     * @param mix|false|null $data The initial data. False is probably an error in the API call
     */
    public function __construct($data = null)
    {
        parent::__construct($data);

        if (is_object($data) && isset($data->pathways)) {
            // Instantiate the pathways
            foreach ($data->pathways as $pathway) {
                $this->pathways[] = new Pathways($pathway);
            }
        }
    }
}
