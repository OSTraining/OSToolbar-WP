<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\View;

defined('OSTEAMMATE_LOADED') or die();

use OSTeammate\Model\Pathways as Model;
use OctopusFrame\Router\Breadcrumbs;


class Pathways extends AbstractView
{
    /**
     * The template name
     *
     * @var string
     */
    protected $templateName = 'pathways';

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
        $breadcrumbs = Breadcrumbs::getInstance();
        $data        = $this->model->getData();

        $data = array(
            'breadcrumbs' => $breadcrumbs->getPathway(),
            'pathways'    => $data
        );

        return parent::getData($data);
    }
}
