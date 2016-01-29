<?php
/**
 * @package   OSTeammateSharedLibrary
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\Platform\WordPress;

use OctopusFrame\Factory;
use OSTeammate\Application\AbstractClient;
use Whoops\Handler\PrettyPageHandler;

defined('OSTEAMMATE_LOADED') or die();


class Client extends AbstractClient
{
    public function init()
    {
        parent::init();

        $container = Factory::getContainer();

        if ($container->api->isConnected()) {
            $container->templateUpdater->checkUpdate(3600);
        }

        add_shortcode('osteammate', array($this, 'replaceShortCode'));

        if ($this->isAdmin()) {
            $admin = new Admin;
            $admin->init();
        }
    }

    public function replaceShortCode($atts)
    {
        $container = Factory::getContainer();

        if (!$container->api->isConnected()) {
            return '<div class="error">Error connecting to OSTeammate API. Please, verify the API token.</div>';
        }

        return $container->client->getView()->getOutput();
    }

    public function isDebug()
    {
        return defined('WP_DEBUG') && WP_DEBUG;
    }

    public function isAdmin()
    {
        return is_admin();
    }

    /**
     * Returns the client's version
     *
     * @return string The version
     */
    public function getVersion()
    {
        if (empty($this->version)) {
            $this->version = '0.0.0';
        }

        return parent::getVersion();
    }
}
