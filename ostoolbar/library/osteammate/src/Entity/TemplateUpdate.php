<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\Entity;

defined('OSTEAMMATE_LOADED') or die();

use stdClass;


/**
 * Class for the Template Update
 */
class TemplateUpdate extends AbstractEntity
{
    /**
     * A list of templates
     *
     * @var array
     */
    public $templates = array();

    /**
     * A base path for assets
     *
     * @var string
     */
    public $assetsBaseURL = null;

    /**
     * A list of CSS files
     *
     * @var array
     */
    public $css = array();

    /**
     * A list of JS files
     *
     * @var array
     */
    public $js = array();

    /**
     * A list of images
     *
     * @var array
     */
    public $img = array();

    /**
     * Default constructor. It can set the internal attributes based on a
     * stdClass object result of a JSON decode procedure.
     *
     * @param mix|false|null $data The initial data. False is probably an error in the API call
     */
    public function __construct($data = null)
    {
        parent::__construct($data);

        if (is_object($data)) {
            if (isset($data->templates)) {
                $this->templates = array();

                // Instantiate the templates
                foreach ($data->templates as $name => $content) {
                    $template          = new stdClass;
                    $template->name    = $name;
                    $template->content = $content;
                    $this->templates[] = new Template($template);
                }
            }

            if (isset($data->assets)) {
                if (isset($data->assets->base_url)) {
                    $this->assetsBaseURL = $data->assets->base_url;
                }

                if (isset($data->assets->css)) {
                    $this->css = $data->assets->css;
                }

                if (isset($data->assets->js)) {
                    $this->js = $data->assets->js;
                }

                if (isset($data->assets->img)) {
                    $this->img = $data->assets->img;
                }
            }
        }
    }
}
