<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\View;

defined('OSTEAMMATE_LOADED') or die();

use OctopusFrame\Factory;
use OctopusFrame\Router\Breadcrumbs;
use OSTeammate\Entity\Template;


class Lesson extends AbstractOverridableView
{
    /**
     * The template name
     *
     * @var string
     */
    protected $templateName = 'lesson';

    /**
     * Returns the data to be used to render the template. This method should be overriden by
     * every child class
     *
     * @param array $data An initial data
     *
     * @return array The data for templates
     */
    protected function getData(array $data = array())
    {
        $router = Factory::getContainer()->router;

        $pathwayId = (int)$router->getParam('pathway_id');
        $courseId  = (int)$router->getParam('course_id');
        $lessonId  = (int)$router->getParam('lesson_id');

        $this->model->setState('pathway.id', $pathwayId);
        $this->model->setState('course.id', $courseId);
        $this->model->setState('lesson.id', $lessonId);
        $data = $this->model->getData();

        // Check if the API is taking care of the submission form
        $this->checkFormSubmissionAndRouteToAPI($data);

        if (isset($data['override']) && $data['override']) {
            return parent::getData($data);
        }

        $pathway     = $data['pathway'];
        $course      = $data['course'];
        $lesson      = $data['lesson'];
        $breadcrumbs = Breadcrumbs::getInstance();

        $breadcrumbs->addItem(
            $pathway->title,
            $router->route(
                array(
                    'view'       => 'courses',
                    'pathway_id' => $pathwayId
                )
            )
        );
        $breadcrumbs->addItem(
            $course->title,
            $router->route(
                array(
                    'view'       => 'course',
                    'pathway_id' => $pathwayId,
                    'course_id'  => $courseId
                )
            )
        );
        $breadcrumbs->addItem(
            $lesson->title,
            $router->route(
                array(
                    'view'       => 'lesson',
                    'pathway_id' => $pathwayId,
                    'course_id'  => $courseId,
                    'lesson_id'  => $lessonId
                )
            )
        );

        // Decode the lesson content
        $lesson->content = base64_decode($lesson->content);

        // Module title
        $moduleTitle = '';
        if (!empty($lesson->totalModules)) {
            $moduleTitle = ($lesson->currentModuleIndex + 1) . '/' . $lesson->totalModules;
        }

        // Lesson title
        $lessonTitle = '';
        if (!empty($lesson->totalLessons)) {
            $lessonTitle = ($lesson->currentLessonIndex + 1) . '/' . $lesson->totalLessons;
        }

        $data = array(
            'breadcrumbs'  => $breadcrumbs->getPathway(),
            'pathway'      => $pathway,
            'course'       => $course,
            'lesson'       => $lesson,
            'module_title' => $moduleTitle,
            'lesson_title' => $lessonTitle
        );

        return parent::getData($data);
    }
}
