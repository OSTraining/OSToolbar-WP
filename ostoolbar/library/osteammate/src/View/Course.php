<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\View;

defined('OSTEAMMATE_LOADED') or die();

use OctopusFrame\Router\Breadcrumbs;
use OctopusFrame\Factory;


class Course extends AbstractView
{
    /**
     * The template name
     *
     * @var string
     */
    protected $templateName = 'course';

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

        $this->model->setState('pathway.id', $pathwayId);
        $this->model->setState('course.id', $courseId);
        $data = $this->model->getData();

        $breadcrumbs = Breadcrumbs::getInstance();
        $breadcrumbs->addItem(
            $data['pathway']->title,
            $router->route(
                array(
                    'view'       => 'courses',
                    'pathway_id' => $pathwayId
                )
            )
        );
        $breadcrumbs->addItem(
            $data['course']->title,
            $router->route(
                array(
                    'view'       => 'course',
                    'pathway_id' => $pathwayId,
                    'course_id'  => $courseId
                )
            )
        );

        $data['course']->level = ucfirst($data['course']->level);

        $data = array(
            'breadcrumbs' => $breadcrumbs->getPathway(),
            'pathway'     => $data['pathway'],
            'course'      => $data['course']
        );

        return parent::getData($data);
    }
}
