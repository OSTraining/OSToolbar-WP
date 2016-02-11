<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\View;

use OctopusFrame\Router\Breadcrumbs;
use OctopusFrame\Factory;
use OSTeammate\Model\Courses as Model;

defined('OSTEAMMATE_LOADED') or die();


class Courses extends AbstractView
{
    /**
     * The template name
     *
     * @var string
     */
    protected $templateName = 'courses';

    /**
     * Returns the data to be used to render the template. This method should be overriden by
     * every child class
     *
     * @param array $data An initial data
     *
     * @return array The data for templates
     */
    public function getData(array $data = array())
    {
        $router    = Factory::getContainer()->router;
        $pathwayId = (int)$router->getParam('pathway_id');

        $this->model->setState('pathway.id', $pathwayId);
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

        $data = array(
            'breadcrumbs' => $breadcrumbs->getPathway(),
            'pathway'     => $data['pathway'],
            'courses'     => $data['courses']
        );

        return parent::getData($data);
    }
}
