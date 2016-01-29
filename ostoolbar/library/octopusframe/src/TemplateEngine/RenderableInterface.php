<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\TemplateEngine;

defined('OCTOPUSFRAME_LOADED') or die();


interface RenderableInterface
{
    /**
     * Set the theme to be used
     *
     * @param string $theme The theme name
     */
    public function setTheme($theme);

    /**
     * Method to render the template and return the output.
     *
     * @param string $templateName The template name
     * @param array  $variables    The variables used in the templates
     *
     * @return string The rendered output
     */
    public function render($templateName, array $variables = array());

    /**
     * Returns the templates folder path
     *
     * @return string The templates folder full path
     */
    public function getTemplatesPath();

    /**
     * Returns the assets path
     *
     * @return string The assets folder full path
     */
    public function getAssetsPath();

    /**
     * Returns the templates file extension
     *
     * @return string The file extension
     */
    public function getTemplateFileExtension();
}
