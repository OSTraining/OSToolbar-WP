<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\Entity;

use stdClass;

defined('OSTEAMMATE_LOADED') or die();


/**
 * Class for the Template
 */
class Template extends AbstractEntity
{
    /**
     * The template name, in lowercase.
     *
     * @var string
     */
    public $name = null;

    /**
     * The template code
     *
     * @var string
     */
    public $content = null;

    /**
     * Default constructor. It can set the internal attributes based on a
     * stdClass object result of a JSON decode procedure.
     *
     * @param mix|false|null $data The initial data. False is probably an error in the API call
     */
    public function __construct($data = null)
    {
        parent::__construct($data);

        if (is_object($data) && !empty($this->content)) {
            $this->content = base64_decode($this->content);
        }
    }
}
