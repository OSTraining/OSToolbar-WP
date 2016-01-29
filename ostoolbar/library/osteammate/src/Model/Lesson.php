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
 * Model for training lesson
 */
class Lesson extends AbstractModel
{
    /**
     * Returns a list of Courses
     *
     * @return array An array with the lesson, the pathway, course, or a template override
     */
    public function getData()
    {
        if (empty($this->data)) {
            $pathwayId  = $this->getState('pathway.id');
            $courseId   = $this->getState('course.id');
            $lessonId   = $this->getState('lesson.id');
            $this->data = Factory::getContainer()->api->getLesson($pathwayId, $courseId, $lessonId);
        }

        return parent::getData();
    }
}
