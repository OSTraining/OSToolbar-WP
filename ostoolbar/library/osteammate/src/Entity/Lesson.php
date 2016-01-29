<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\Entity;

defined('OSTEAMMATE_LOADED') or die();


/**
 * Class for the Lesson
 */
class Lesson extends AbstractEntity
{
    /**
     * The ID of this lesson
     *
     * @var int
     */
    public $id = 0;

    /**
     * The title of this lesson
     *
     * @var string
     */
    public $title = null;

    /**
     * The alias of this lesson
     *
     * @var string
     */
    public $alias = null;

    /**
     * The type of this lesson
     *
     * @var string
     */
    public $type = null;

    /**
     * The content of this lesson
     *
     * @var string
     */
    public $content = null;

    /**
     * The total of lessons available for this course
     *
     * @var int
     */
    public $totalLessons = null;

    /**
     * The total of modules available for this course
     *
     * @var int
     */
    public $totalModules = null;

    /**
     * The index of the current lesson
     *
     * @var int
     */
    public $currentLessonIndex = null;

    /**
     * The index of the current module
     *
     * @var int
     */
    public $currentModuleIndex = null;

    /**
     * The ID of the previous lesson
     *
     * @var int
     */
    public $previousLessonId = null;

    /**
     * The ID of the next lesson
     *
     * @var int
     */
    public $nextLessonId = null;
}
