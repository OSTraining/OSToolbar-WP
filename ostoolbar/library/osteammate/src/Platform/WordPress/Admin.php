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
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
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
            'OSToolbar Settings',
            'OSToolbar',
            'manage_options',
            static::SETTINGS_PAGE,
            array($this, 'createSettingsPage')
        );

        // Add page to the main menu
        add_object_page(
            'OSToolbar',
            'OSToolbar',
            'see_ostoolbar_videos',
            'ostoolbar',
            array(
                $this,
                'createObjectPage'
            )
        );
    }

    /**
     * Options page callback
     */
    public function createSettingsPage()
    {
        echo '<div class="wrap">';
        echo '<h2>OSToolbar Settings</h2>';
        echo '<form method="post" action="options.php">';

        // This prints out all hidden setting fields
        settings_fields(static::SETTINGS_GROUP);
        do_settings_sections(static::SETTINGS_PAGE);
        submit_button();

        echo '</form>';
        echo '</div>';
    }

    public function createObjectPage()
    {
        $container = Factory::getContainer();

        // Check the user capabilities
        if ($container->access->hasAccess('see_ostoolbar_videos')) {
            echo '<div id="ostoolbar_wp_wrapper">';

            if (!$container->api->isConnected()) {
                echo '<div class="error">Error connecting to OSTeammate API. Please, verify the API token.</div>';
            }

            echo $container->client->getView()->getOutput();

            echo '</div>';
        }
    }

    /**
     * Register and add settings
     */
    public function pageInit()
    {
        add_settings_section(
            static::SETTINGS_SECTION,
            __('Basic Settings', 'ostoolbar'),
            array($this, 'printSectionInfo'),
            static::SETTINGS_PAGE
        );

        register_setting(
            static::SETTINGS_GROUP,
            static::OPTION_NAME,
            array($this, 'sanitize')
        );

        add_settings_field(
            'token',
            __('API Token', 'ostoolbar'),
            array($this, 'tokenField'),
            static::SETTINGS_PAGE,
            static::SETTINGS_SECTION
        );

        // Toolbar permissions
        add_settings_field(
            'permissions',
            __('Choose which users can see videos', 'ostoolbar'),
            array($this, 'permissionField'),
            static::SETTINGS_PAGE,
            static::SETTINGS_SECTION
        );

        add_settings_section(
            static::SETTINGS_SECTION . '_feedback',
            __('Feedback', 'ostoolbar'),
            array($this, 'printFeedbackForm'),
            static::SETTINGS_PAGE
        );
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

        if (isset($input['permissions'])) {
            $newInput['permissions'] = preg_replace('/[^a-z0-9",\-_:\{\}]/i', '', $input['permissions']);
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
     * Print the Feedback Section text
     */
    public function printFeedbackForm()
    {
        $user      = wp_get_current_user(); // @todo: Create a platform-specific user class
        $container = Factory::getContainer();

        echo '<button id="ostoolbar-feedback-btn">Provide Feedback</button>';
        echo '<script type="text/javascript" src="https://ostraining.atlassian.net/s/f25af8f2d88b3e09d0426cd659a2d113-T/en_US-fd2bae/71001/30d688cae3a5e0b7b42a9da7fdb0e0bd/2.0.10/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector.js?locale=en-US&collectorId=2dc2d5de"></script>
              <script type="text/javascript">
                  window.ATL_JQ_PAGE_PROPS =  {
                      "triggerFunction": function(showCollectorDialog) {
                          //Requires that jQuery is available!
                          jQuery("#ostoolbar-feedback-btn").click(function(e) {
                              e.preventDefault();
                              showCollectorDialog();
                          });
                      },
                      fieldValues: {
                          components: "10302",
                          fullname: "' . $user->display_name . '",
                          email: "' . $user->user_email . '"
                      },
                      environment : {
                          "Domain": "' . $container->client->getDomain() . '",
                          "OSTeammate API Token": "' . $container->configuration->get('token') . '",
                          "Platform / Version": "' . $container->platform . ' / ' . get_bloginfo('version') . '",
                          "Package Version": "' . $container->client->getVersion() . '"
                      }
                  };
              </script>';
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
     * Permission field
     */
    public function permissionField()
    {
        $config = Factory::getContainer()->configuration;
        $permissions = $config->getPermissions();
        ?>
        <div id="ostoolbar-settings-panel"></div>
        <script id='ostoolbar-settings-template' type='text/ractive'>
            <table border="0" cellpadding="0" cellspacing="0">
                {{#each permissions}}
                    <tr>
                        <td>{{role}}</td>
                        <td>
                            <input
                                type="checkbox"
                                {{#if name == 'administrator'}}disabled{{/if}}
                                {{#if allowed == 1}}checked="checked"{{/if}}
                                id="chk_{{name}}"
                                class="role_permission"
                                data-name="{{name}}"
                                on-change="updatePermissions" />
                        </td>
                    </tr>
                {{/each}}
            </table>
            <input
                type="hidden"
                name="<?php echo static::OPTION_NAME; ?>[permissions]"
                id="permissions"
                value="{{json}}" />
        </script>
        <input
            type="hidden"
            name="current-permissions"
            id="current-permissions"
            value="<?php echo esc_attr(json_encode($permissions)); ?>" />
        <?php
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
            echo '<div class="error"><p>Error connecting to OSToolbar API. Please, <a href="options-general.php?page=ostoolbar_settings">verify the API token</a>.</p></div>';
        }
    }

    /**
     * Enqueue scripts for the admin panel
     *
     * @param      string  $hook   The current hook
     */
    public function enqueueScripts($hook)
    {
        if ($hook === 'settings_page_ostoolbar_settings') {
            wp_register_script('ostoolbar-ractive', 'http://cdn.ractivejs.org/latest/ractive.js');

            // Add configuration scripts/css
            wp_register_style(
                'ostoolbar-configuration',
                $this->getUrl(OSTOOLBAR_ASSETS_PATH . '/css/configuration.css')
            );
            wp_enqueue_style('ostoolbar-configuration');

            wp_register_script(
                'ostoolbar-configuration',
                $this->getUrl(OSTOOLBAR_ASSETS_PATH . '/js/configuration.js'),
                array(
                    'ostoolbar-ractive',
                    'jquery-core'
                )
            );
            wp_enqueue_script('ostoolbar-configuration');
        } elseif ($hook === 'toplevel_page_ostoolbar') {
            // Add configuration scripts/css
            wp_register_style(
                'ostoolbar-style',
                $this->getUrl(OSTOOLBAR_ASSETS_PATH . '/css/ostoolbar.css')
            );
            wp_enqueue_style('ostoolbar-style');
        }
    }

    /**
     * Returns the url for a plugin path
     *
     * @param      string  $path   The path
     *
     * @return     string
     */
    public function getUrl($path)
    {
        $base = plugin_dir_url($path);

        return $base . basename($path);
    }
}