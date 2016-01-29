<?php
/**
 * @package   OSTeammateSharedLibrary
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\Platform\WordPress;

use OctopusFrame\Factory;

defined('OSTEAMMATE_LOADED') or die();


class Admin
{
    const SETTINGS_PAGE    = 'ostoolbar_settings';
    const SETTINGS_SECTION = 'ostoolbar_settings_section';
    const SETTINGS_GROUP   = 'ostoolbar_settings_group';
    const OPTION_NAME      = 'ostoolbar_settings';

    public function init()
    {
        add_action('admin_menu', array($this, 'addPluginPage'));
        add_action('admin_init', array($this, 'pageInit'));
        add_action('admin_notices', array($this, 'adminNotice'));
    }

    /**
     * Add options page
     */
    public function addPluginPage()
    {
        // This page will be under "Settings"
        add_options_page(
            'OSToolBar Settings',
            'OSToolBar',
            'manage_options',
            static::SETTINGS_PAGE,
            array($this, 'createAdminPage')
        );
    }

    /**
     * Options page callback
     */
    public function createAdminPage()
    {
        echo '<div class="wrap">';
        echo '<h2>OSToolBar Settings</h2>';
        echo '<form method="post" action="options.php">';

        // This prints out all hidden setting fields
        settings_fields(static::SETTINGS_GROUP);
        do_settings_sections(static::SETTINGS_PAGE);
        submit_button();

        echo '</form>';
        echo '</div>';
    }

    /**
     * Register and add settings
     */
    public function pageInit()
    {
        register_setting(
            static::SETTINGS_GROUP,
            static::OPTION_NAME,
            array($this, 'sanitize')
        );

        add_settings_section(
            static::SETTINGS_SECTION,
            'Basic Settings',
            array($this, 'printSectionInfo'),
            static::SETTINGS_PAGE
        );

        add_settings_field(
            'token',
            'API Token',
            array($this, 'tokenField'),
            static::SETTINGS_PAGE,
            static::SETTINGS_SECTION
        );


        // // Toolbar permissions
        // add_settings_field(
        //     'ostoolbar_permissions',
        //     __('Choose which users can see videos', 'ostoolbar'),
        //     array(
        //         $this,
        //         'toolbarPermissionField'
        //     ),
        //     static::SETTINGS_PAGE,
        //     static::SETTINGS_SECTION
        // );
        // register_setting(static::SETTINGS_GROUP, 'ostoolbar_permissions');
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        $newInput = array();
        if (isset($input['token'])) {
            $newInput['token'] = sanitize_text_field($input['token']);
        }

        return $newInput;
    }

    /**
     * Print the Section text
     */
    public function printSectionInfo()
    {
        echo 'Enter your settings below:';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function tokenField()
    {
        $config = Factory::getContainer()->configuration;

        printf(
            '<input type="text" id="token" name="' . static::OPTION_NAME . '[token]" value="%s" style="width: 300px;" />',
            esc_attr($config->get('token'))
        );
    }

    /**
     * Display the admin messages
     *
     * @return void
     */
    public function adminNotice()
    {
        $container = Factory::getContainer();
        if (!$container->api->isConnected()) {
            echo '<div class="error"><p>Error connecting to OSToolBar API. Please, <a href="options-general.php?page=ostoolbar_settings">verify the API token</a>.</p></div>';
        }
    }
}
