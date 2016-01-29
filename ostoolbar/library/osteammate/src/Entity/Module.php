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
 * Class for the Module
 */
class Module extends AbstractEntity
{
    /**
     * The ID of this module
     *
     * @var int
     */
    public $id = 0;

    /**
     * The title of this module
     *
     * @var string
     */
    public $title = null;

    /**
     * The lessons of this module
     *
     * @var array
     */
    public $lessons = array();

    /**
     * Default constructor. It can set the internal attributes based on a
     * stdClass object result of a JSON decode procedure.
     *
     * @param mix|false|null $data The initial data. False is probably an error in the API call
     */
    public function __construct($data = null)
    {
        parent::__construct($data);

        if (is_object($data) && isset($data->lessons)) {
            $this->lessons = array();

            // Instantiate the lessons list
            foreach ($data->lessons as $lesson) {
                $this->lessons[] = new Lesson($lesson);
            }
        }
    }
}
