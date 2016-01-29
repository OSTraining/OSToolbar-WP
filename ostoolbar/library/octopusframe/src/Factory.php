<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame;

defined('OCTOPUSFRAME_LOADED') or die();

use OctopusFrame\Registry\RegistrableInterface;
use OctopusFrame\Container;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\PlainTextHandler;
use UnexpectedValueException;

/**
 * Global factory class
 */
abstract class Factory
{
    /**
     * OSTeammate container instance
     *
     * @var Container
     */
    protected static $container = null;

    /**
     * Get a OSTeammate container class
     *
     * Required options:
     *     - platform : Platform name, to identify the correct namespace for adapters
     *     - cachePath: Path for the cache folder
     *     - logPath  : Path for the log folder
     *     - services : An instance implementing ServiceProviderInterface
     *
     * @param RegistrableInterface $options Options to create a new container
     *
     * @return Container
     * @throws Exception
     */
    public static function getContainer(RegistrableInterface $options = null)
    {
        if (empty(static::$container)) {
            if (!is_object($options)) {
                throw new UnexpectedValueException('Required options to instantiate the container');
            }

            // Validate the params
            if (!$options->exists('platform')) {
                throw new UnexpectedValueException('Invalid platform');
            }

            if (!$options->exists('cachePath')) {
                throw new UnexpectedValueException('Invalid cachePath');
            }

            if (!$options->exists('logPath')) {
                throw new UnexpectedValueException('Invalid logPath');
            }

            if (!$options->exists('services')) {
                throw new UnexpectedValueException('Invalid services');
            }

            $container = new Container(
                array(
                    'platform'  => $options->get('platform'),
                    'cachePath' => $options->get('cachePath'),
                    'logPath'   => $options->get('logPath')
                )
            );
            $container->register($options->get('services'));

            // Add an error/exception handler
            if ($container->client->isDebug()) {
                $handler = new PrettyPageHandler;
            } else {
                $handler = new PlainTextHandler($container->log);
                $handler->loggerOnly(true);
            }
            $container->whoops->pushHandler($handler);
            $container->whoops->register();

            static::$container = $container;
        }

        return static::$container;
    }
}
