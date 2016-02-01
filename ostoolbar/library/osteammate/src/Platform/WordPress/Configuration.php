<?php
/**
 * @package   OSTeammateSharedLibrary
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\Platform\WordPress;

use OctopusFrame\Platform\WordPress\Configuration as BaseConfiguration;

defined('OSTEAMMATE_LOADED') or die();


class Configuration extends BaseConfiguration
{
    const OPTION_NAME = 'ostoolbar_settings';

    public function getPermissions()
    {
        /** @var \WP_Roles $wp_roles */
        global $wp_roles;

        $allRoles = $wp_roles->roles;
        $current  = json_decode($this->get('permissions'), true) ?: array();

        $permissions = array();
        foreach ($allRoles as $key => $role) {
            $optSet  = (isset($current[$key]) && $current[$key]) || $key == 'administrator';
            $roleSet = (isset($role['capabilities'][$key]) && $role['capabilities'][$key]);

            $permissions[$key] = array(
                'name'    => $key,
                'role'    => $role['name'],
                'allowed' => $optSet || $roleSet
            );
        }

        return $permissions;
    }
}
