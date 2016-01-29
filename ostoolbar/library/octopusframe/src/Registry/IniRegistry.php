<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Registry;

use UnexpectedValueException;

defined('OCTOPUSFRAME_LOADED') or die();


/**
 * Defines the abstract class for store of parameters in a INI file.
 * Initial implementation that doesn't support section.
 */
class IniRegistry extends ArrayRegistry
{
    /**
     * The INI file path
     * @var string
     */
    protected $filePath;

    /**
     * Constructor
     *
     * @param mixed                $storage The parameters storage
     * @param RegistrableInterface $options The options
     */
    public function __construct($storage = null, RegistrableInterface $options = null)
    {
        $filePath = $storage;
        $storage  = array();

        if (!is_string($filePath)) {
            throw new UnexpectedValueException("Invalid file path");
        }

        $this->filePath = $filePath;

        // Load current data
        if (file_exists($this->filePath)) {
            $storage = parse_ini_file($this->filePath);
        }

        parent::__construct($storage);
    }

    /**
     * Save the whole registry
     */
    public function save()
    {
        $config = '';

        foreach ($this->storage as $key => $value) {
            $value  = (string)$value;
            $config .= "{$key}=\"{$value}\"\n";
        }

        file_put_contents($this->filePath, $config);
    }

    /**
     * Returns true if the registry key is set
     *
     * @param string $key The key you are looking for
     *
     * @return bool True, if is set
     */
    public function exists($key)
    {
        return array_key_exists($key, $this->storage);
    }
}
