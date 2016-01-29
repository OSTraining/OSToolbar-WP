<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\TemplateEngine;

defined('OCTOPUSFRAME_LOADED') or die();

use OctopusFrame\Factory;


abstract class AbstractEngine implements RenderableInterface
{
    /**
     * The templates folder path
     *
     * @var string
     */
    protected $templatesPath;

    /**
     * The assets folder url
     *
     * @var string
     */
    protected $assetsURL;

    /**
     * The assets folder path
     *
     * @var string
     */
    protected $assetsPath;

    /**
     * The theme to be used
     *
     * @var string
     */
    protected $theme = 'default';

    /**
     * The template file extension
     *
     * @var string
     */
    protected $templateFileExtension = '.html';

    /**
     * The external template engine
     *
     * @var mixed
     */
    protected $engine;

    /**
     * Method to initialise the engine
     *
     * @param  string $templatesPath The template folder full path
     * @param  string $assetsURL     The assets folder url
     * @param  string $assetsPath    The assets folder full path
     */
    public function __construct($templatesPath, $assetsURL = '', $assetsPath = '')
    {
        $this->templatesPath = $templatesPath;
        $this->assetsURL     = $assetsURL;
        $this->assetsPath    = $assetsPath;

        // Make sure the template dir exists
        $container = Factory::getContainer();
        if (!$container->folder->exists($templatesPath)) {
            $container->folder->create($templatesPath);
        }
    }

    /**
     * Get the default variables for the template
     *
     * @return array Array with default variables
     */
    protected function getDefaultTemplateVariables()
    {
        $container = Factory::getContainer();

        return array(
            'theme'          => $this->theme,
            'script_url'     => $_SERVER['SCRIPT_NAME'],
            'request_uri'    => $_SERVER['REQUEST_URI'],
            'api_url'        => $container->api->getUrl()
        );
    }

    /**
     * Returns the variables for templates mergin with the default variables
     *
     * @param  array $variables An array of custom variables
     * @return array            An array of variables
     */
    protected function getTemplateVariables(array $variables = array())
    {
        return array_merge($this->getDefaultTemplateVariables(), $variables);
    }

    /**
     * Set the theme to be used
     *
     * @param string $theme The theme name
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * Method to render the template and return the output.
     *
     * @param string $templateName The template name
     * @param array  $variables    The variables used in the templates
     *
     * @return string The rendered output
     */
    public function render($templateName, array $variables = array())
    {
        return '';
    }

    /**
     * Returns the template path
     *
     * @return string The template folder full path
     */
    public function getTemplatesPath()
    {
        return $this->templatesPath;
    }

    /**
     * Returns the assets url
     *
     * @return string The assets folder url
     */
    public function getAssetsURL()
    {
        return $this->assetsURL;
    }

    /**
     * Returns the assets path
     *
     * @return string The assets folder full path
     */
    public function getAssetsPath()
    {
        return $this->assetsPath;
    }

    /**
     * Returns the templates file extension
     *
     * @return string The file extension
     */
    public function getTemplateFileExtension()
    {
        return $this->templateFileExtension;
    }
}
