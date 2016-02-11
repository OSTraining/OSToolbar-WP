<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\View;

defined('OSTEAMMATE_LOADED') or die();


interface ViewableInterface
{
    /**
     * Returns the output of the view, rendered.
     *
     * @return string The rendered output
     */
    public function getOutput();

    /**
     * Returns an instance of the model
     *
     * @return StatableInterface The model instance
     */
    public function getModel();

    /**
     * Returns the view's title
     *
     * @return string The view's title
     */
    public function getTitle();

    /**
     * Returns the data to be used to render the template. This method should be overriden by
     * every child class
     *
     * @param array $data An initial data
     *
     * @return array The data for templates
     */
    public function getData(array $data = array());
}
