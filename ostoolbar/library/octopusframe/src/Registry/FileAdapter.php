<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Registry;

use OctopusFrame\Factory;
use UnexpectedValueException;
use phpfastcache;

defined('OCTOPUSFRAME_LOADED') or die();


/**
 * Class for the File cache
 */
class FileAdapter extends PHPFastCacheAdapter
{
    /**
     * The storage path
     * @var string
     */
    protected $path;

    /**
     * Constructor
     *
     * @param mixed                $storage The parameters storage
     * @param RegistrableInterface $options The options
     */
    public function __construct($storage = null, RegistrableInterface $options = null)
    {
        $this->path = $options->get('path', null);

        if (is_null($this->path)) {
            throw new UnexpectedValueException("Invalid option: path");
        }

        if (!file_exists($this->path)) {
            $container = Factory::getContainer();

            if (!$container->folder->exists($this->path)) {
                $container->folder->create($this->path);
            }

            if (!file_exists($this->path)) {
                throw new UnexpectedValueException("Invalid option: path. The path doesn't exists and couldn't be created");
            }
        }

        phpfastcache::setup('storage', 'file');
        phpfastcache::setup('path', $this->path);

        parent::__construct($storage);
    }

    /**
     * Returns the path
     *
     * @return string The path
     */
    public function getPath()
    {
        return $this->path;
    }
}
