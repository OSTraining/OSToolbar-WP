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
use OSTeammate\Exception\InvalidSignature;


class AbstractView implements ViewableInterface
{
    /**
     * An instance of template engine
     *
     * @var RenderableInterface
     */
    protected $templateEngine;

    /**
     * The view's model
     *
     * @var StatableInterface
     */
    protected $model;

    /**
     * The view's title
     *
     * @var string
     */
    protected $title = '';

    /**
     * The template name
     *
     * @var string
     */
    protected $templateName = '';

    /**
     * Class constructor that instantiates the model and set some attributes.
     */
    public function __construct()
    {
        $this->templateEngine = Factory::getContainer()->templateEngine;

        // Instantiate the model for this view
        $modelName   = str_replace('OSTeammate\\View\\', '', get_class($this));
        $modelClass  = "OSTeammate\\Model\\{$modelName}";
        $this->model = new $modelClass;
    }

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
        if (!isset($data['theme'])) {
            $data['theme'] = Factory::getContainer()->client->getTheme();
        }

        return $data;
    }

    /**
     * Returns an instance of the model
     *
     * @return StatableInterface The model instance
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Returns the view's title
     *
     * @return string The view's title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the output of the view, rendered.
     *
     * @return string The rendered output
     */
    public function getOutput()
    {
        try {
            $data = $this->getData();
        } catch (InvalidSignature $e) {
            $data = array();
            $this->templateName = 'access_denied';
        }

        return $this->templateEngine->render($this->templateName, $data);
    }
}
