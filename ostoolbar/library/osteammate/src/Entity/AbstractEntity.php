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
 * Base class for entities
 */
abstract class AbstractEntity
{
    /**
     * Default constructor. It can set the internal attributes based on a
     * stdClass object result of a JSON decode procedure.
     *
     * @param mix|false|null $data The initial data. False is probably an error in the API call
     */
    public function __construct($data = null)
    {
        if (is_object($data)) {
            $internalAttributes = get_object_vars($this);

            foreach ($data as $attribute => $value) {
                $attribute = $this->getCamelCaseString($attribute);
                if (array_key_exists($attribute, $internalAttributes)) {
                    $this->$attribute = $value;
                }
            }
        }
    }

    /**
     * Converts _ to a camel case format
     *
     * @param  string $string The string to be converted
     *
     * @return string         The string in a camelcase format
     */
    protected function getCamelCaseString($string)
    {
        while ($index = strpos($string, '_')) {
            $newString = substr($string, 0, $index);
            $newString .= ucfirst(substr($string, $index + 1));
            $string = $newString;
        }

        return $string;
    }
}
