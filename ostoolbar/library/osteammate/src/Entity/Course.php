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
 * Class for the Course
 */
class Course extends AbstractEntity
{
    /**
     * The ID of this course
     *
     * @var int
     */
    public $id = 0;

    /**
     * The title of this course
     *
     * @var string
     */
    public $title = null;

    /**
     * The description of this course
     *
     * @var string
     */
    public $description = null;

    /**
     * The intro text of this course
     *
     * @var string
     */
    public $introtext = null;

    /**
     * The relative path for the picture of this course
     *
     * @var string
     */
    public $picture = null;

    /**
     * The level of this course
     *
     * @var string
     */
    public $level = null;

    /**
     * The length of this course, in minutes
     *
     * @var int
     */
    public $length = null;

    /**
     * The teacher of this course
     *
     * @var string
     */
    public $teacher = null;

    /**
     * The first lesson title
     *
     * @var string
     */
    public $firstLessonTitle = null;

    /**
     * The first lesson id
     *
     * @var int
     */
    public $firstLessonId = null;

    /**
     * The modules of this course
     *
     * @var array
     */
    public $modules = array();

    /**
     * Default constructor. It can set the internal attributes based on a
     * stdClass object result of a JSON decode procedure.
     *
     * @param mix|false|null $data The initial data. False is probably an error in the API call
     */
    public function __construct($data = null)
    {
        parent::__construct($data);

        if (is_object($data) && isset($data->modules)) {
            $this->modules = array();

            // Instantiate the modules and lessons list
            foreach ($data->modules as $module) {
                $this->modules[] = new Module($module);
            }
        }

        if (!empty($this->firstLessonId)) {
            $this->firstLessonId = (int)$this->firstLessonId;
        }

        if (!empty($this->length)) {
            $this->length = (int)$this->length;
        }

        if (!empty($this->level)) {
            $this->level = ucfirst($this->level);
        }
    }
}
