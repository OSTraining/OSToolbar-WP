<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\TemplateEngine;

defined('OCTOPUSFRAME_LOADED') or die();

use Twig_Loader_Filesystem;
use Twig_Extension_StringLoader;
use Twig_Environment;
use OctopusFrame\TemplateEngine\Extension\Helper;


/**
 * The Twig adapter for template engine
 */
class Twig extends AbstractEngine
{
    /**
     * Method to initialise the engine
     *
     * @param  string $templatesPath The template folder full path
     * @param  string $assetsURL     The assets folder url
     * @param  string $assetsPath    The assets folder full path
     */
    public function __construct($templatesPath, $assetsURL = '', $assetsPath = '')
    {
        parent::__construct($templatesPath, $assetsURL, $assetsPath);

        $loader  = new Twig_Loader_Filesystem($this->templatesPath);

        // @TODO: enable cache? It is disabled now because was messing the updates. Clean the cache after update?
        // @TODO: Should we define a sandbox policy? $this->engine->addExtension(new Twig_Extension_Sandbox());
        $options = array(
            // 'cache' => '/tmp/twig'
        );

        $this->engine = new Twig_Environment($loader, $options);
        $this->engine->addExtension(new Twig_Extension_StringLoader());
        $this->engine->addExtension(new Helper());
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
        $variables = $this->getTemplateVariables($variables);

        return $this->engine->render($templateName . '.html', $variables);
    }
}
