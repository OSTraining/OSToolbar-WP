<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
namespace Ostoolbar;

defined('ABSPATH') or die();

class Application
{
    /**
     * Main entry point/Initialize everything
     */
    public function init()
    {
        $this->setCapabilities();

        $config = Factory::getConfiguration();
        add_action('admin_init', array($config, 'initSettings'));
        add_action('admin_menu', array($this, 'initAdminLinks'));
    }

    protected function setCapabilities()
    {
        $permissions = get_option('ostoolbar_permissions');
        if ($permissions) {
            $permissions = json_decode($permissions, true);

        } else {
            global $wp_roles;
            if (!$wp_roles) {
                $wp_roles = new \WP_Roles;
            }
            $permissions = $wp_roles->role_names;
            foreach ($permissions as $key => $value) {
                $permissions[$key] = (int)($key == 'administrator');
            }
        }

        foreach ($permissions as $key => $allowed) {
            if ($allowed) {
                get_role($key)->add_cap('ostoolbar_see_videos');
            } else {
                get_role($key)->remove_cap('ostoolbar_see_videos');
            }
        }
    }

    /**
     * Initialize the administrator pages
     */
    public function initAdminLinks()
    {
        $controller = Factory::getController();

        add_object_page(
            'OSToolbar',
            'OSToolbar',
            'ostoolbar_see_videos',
            'ostoolbar',
            array(
                $controller,
                'actionTutorials'
            ),
            ''
        );

        add_options_page(
            __('OSToolbar Configuration', 'ostoolbar'),
            'OSToolbar',
            'manage_options',
            'ostoolbar_options',
            array(
                $controller,
                'actionConfiguration'
            )
        );
    }

    public function getUrl($path)
    {
        $base = plugin_dir_url($path);

        return $base . basename($path);
    }

    public function enqueueScripts($hook)
    {
        if ($hook = 'settings_page_options-ostoolbar') {
            $app = Factory::getApplication();

            // Add jQuery-ui support
            wp_register_style(
                'ostoolbar-jquery-ui',
                $app->getUrl(OSTOOLBAR_ASSETS_PATH . '/css/ui-lightness/jquery-ui.css')
            );
            wp_enqueue_style('ostoolbar-jquery-ui');

            wp_register_script('ostoolbar-jquery-ui', $app->getUrl(OSTOOLBAR_ASSETS_PATH . '/js/jquery-ui.js'));
            wp_enqueue_script('ostoolbar-jquery-ui');

            // Add configuration scripts/css
            wp_register_style('ostoolbar-configuration', $app->getUrl(OSTOOLBAR_ASSETS_PATH . '/css/configuration.css'));
            wp_enqueue_style('ostoolbar-configuration');

            wp_register_script('ostoolbar-configuration', $app->getUrl(OSTOOLBAR_ASSETS_PATH . '/js/configuration.js'));
            wp_enqueue_script('ostoolbar-configuration');
        }
    }

    public function getTrialBanner()
    {
        $apikey = get_option('ostoolbar_apikey');
        $html   = array();
        if (Request::isTrial()) {
            if ($apikey) {
                $html = array(
                    '<div class="error">',
                    'Your API key is invalid. Please enter an API key in',
                    ' <a href="options-general.php?page=ostoolbar_options">OSToolbar settings</a>.',
                    '</div>'
                );

            } else {
                $linkAttribs = array(
                    'href="https://www.ostraining.com/pricing/"',
                    'target="_blank"',
                    'class="btn btn-primary"'
                );
                $text        = sprintf(
                    '%s <a %s>%s</a>',
                    'Want more advanced videos?',
                    join(' ', $linkAttribs),
                    'Go Pro!'
                );

                $html = array(
                    '<div class="alert alert-info text-center">',
                    '<h4>' . $text . '</h4>',
                    '</div>'
                );
            }
        }
        return join("\n", $html);
    }
}
