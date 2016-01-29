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
     * Constructor
     *
     * @param mixed                $storage The parameters storage
     * @param RegistrableInterface $options The options
     */
    public function __construct($storage = null, RegistrableInterface $options = null)
    {
        $path = $options->get('path', null);
        if (is_null($path)) {
            throw new UnexpectedValueException("Invalid option: path");
        }

        if (!file_exists($path)) {
            $container = Factory::getContainer();

            if (!$container->folder->exists($path)) {
                $container->folder->create($path);
            }

            if (!file_exists($path)) {
                throw new UnexpectedValueException("Invalid option: path. The path doesn't exists and couldn't be created");
            }
        }

        phpfastcache::setup('storage', 'file');
        phpfastcache::setup('path', $path);

        parent::__construct($storage);
    }
}
