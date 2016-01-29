<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\Model;

use OctopusFrame\Factory;
use OctopusFrame\Model\AbstractModel;

defined('OSTEAMMATE_LOADED') or die();


/**
 * Model for training pathways
 */
class Pathways extends AbstractModel
{
    /**
     * Returns a list of pathways
     *
     * @return array A list of pathways
     */
    public function getData()
    {
        if (empty($this->data)) {
            $this->data = Factory::getContainer()->api->getPathwaysList();
        }

        return parent::getData();
    }
}
