<?php
/**
 * @package   OSTeammateSharedLibrary
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate;

use Pimple\Container as Pimple;
use Pimple\ServiceProviderInterface;
use OctopusFrame\Container;
use OctopusFrame\Registry\ArrayRegistry;

defined('OSTEAMMATE_LOADED') or die();


/**
 * Class Services
 *
 * Pimple services for OSTeammate. The container must be instantiated with
 * at least the following values:
 *
 *   - platform : The platform name for platform specific adapters
 *   - cachePath: The path for the cache directory
 *
 * @package Simplerenew
 */
class Services implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Pimple $pimple An Container instance
     */
    public function register(Pimple $pimple)
    {
        // Services
        $pimple['log'] = function (Container $c) {
            $adapter = '\\OctopusFrame\\Platform\\' . $c['platform'] . '\\Logger';
            $options = new ArrayRegistry(
                array(
                    'path' => $c['logPath'],
                    'name' => 'osteammate'
                )
            );
            return new $adapter($options);
        };

        $pimple['whoops'] = function (Container $c) {
            $adapter = '\\Whoops\\Run';
            return new $adapter;
        };

        $pimple['configuration'] = function (Container $c) {
            $adapter = '\\OSTeammate\\Platform\\' . $c['platform'] . '\\Configuration';
            return new $adapter;
        };

        $pimple['router'] = function (Container $c) {
            $adapter = '\\OSTeammate\\Platform\\' . $c['platform'] . '\\Router';
            return new $adapter($c['configuration']);
        };

        $pimple['templateEngine'] = function (Container $c) {
            $adapter = '\\OctopusFrame\\TemplateEngine\\Twig';
            // TODO: the assetsBaseURL param should be set by a function for each platform
            return new $adapter(
                $c['cachePath'] . '/templates',
                '',
                $c['cachePath'] . '/assets'
            );
        };

        $pimple['file'] = function (Container $c) {
            $adapter = '\\OctopusFrame\\Platform\\' . $c['platform'] . '\\File';
            return new $adapter;
        };

        $pimple['folder'] = function (Container $c) {
            $adapter = '\\OctopusFrame\\Platform\\' . $c['platform'] . '\\Folder';
            return new $adapter;
        };

        $pimple['access'] = function (Container $c) {
            $adapter = '\\OctopusFrame\\Platform\\' . $c['platform'] . '\\Access';
            return new $adapter;
        };

        $pimple['user'] = function (Container $c) {
            $adapter = '\\OctopusFrame\\Platform\\' . $c['platform'] . '\\User';
            return new $adapter;
        };

        $pimple['cache'] = function (Container $c) {
            $adapter = '\\OctopusFrame\\Platform\\' . $c['platform'] . '\\Cache';
            $options = new ArrayRegistry(
                array(
                    'path'     => $c['cachePath'],
                    'name'     => 'osteammate',
                    'lifeTime' => 7 * 24 * 60
                )
            );
            return new $adapter(null, $options);
        };

        $pimple['client'] = function (Container $c) {
            $adapter = '\\OSTeammate\\Platform\\' . $c['platform'] . '\\Client';
            return new $adapter;
        };

        $pimple['api'] = function (Container $c) {
            $adapter = '\\OSTeammate\\API\\Client';
            $config  = $c['configuration'];

            return new $adapter($config->get('token'));
        };

        $pimple['templateUpdater'] = function (Container $c) {
            $adapter = '\\OSTeammate\\Updater\\Templates';
            return new $adapter;
        };
    }
}
