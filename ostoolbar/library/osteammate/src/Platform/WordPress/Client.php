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
use WP_Roles;

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
        add_shortcode('ostoolbar', array($this, 'replaceShortCode'));

        $this->setCapabilities();

        if ($this->isAdmin()) {
            $admin = new Admin;
            $admin->init();
        }
    }

    public function replaceShortCode($atts)
    {
        $container = Factory::getContainer();

        // Check the user capabilities
        if ($container->access->hasAccess('see_ostoolbar_videos')) {
            if (!$container->api->isConnected()) {
                return '<div class="error">Error connecting to OSTeammate API. Please, verify the API token.</div>';
            }

            return $container->client->getView()->getOutput();
        }

        // If the user can't see the videos, return just an empty string
        return '';
    }

    public function isDebug()
    {
        return defined('WP_DEBUG') && WP_DEBUG;
    }

    public function isAdmin()
    {
        return is_admin();
    }

    protected function setCapabilities()
    {
        $config = Factory::getContainer()->configuration;
        $permissions = $config->get('permissions');

        if ($permissions) {
            $permissions = json_decode($permissions, true);
        } else {
            global $wp_roles;

            if (!$wp_roles) {
                $wp_roles = new WP_Roles;
            }

            $permissions = $wp_roles->role_names;

            foreach ($permissions as $key => $value) {
                $permissions[$key] = (int)($key == 'administrator');
            }
        }

        foreach ($permissions as $key => $allowed) {
            if ($allowed) {
                get_role($key)->add_cap('see_ostoolbar_videos');
            } else {
                get_role($key)->remove_cap('see_ostoolbar_videos');
            }
        }
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
