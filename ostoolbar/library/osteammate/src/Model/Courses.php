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
 * Model for training courses
 */
class Courses extends AbstractModel
{
    /**
     * Returns a list of Courses
     *
     * @return array An array with a list of courses and a pathway
     */
    public function getData()
    {
        if (empty($this->data)) {
            $pathwayId = $this->getState('pathway.id');
            $this->data = Factory::getContainer()->api->getCoursesList($pathwayId);
        }

        return parent::getData();
    }
}
