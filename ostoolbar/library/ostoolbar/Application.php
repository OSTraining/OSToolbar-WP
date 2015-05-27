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

        add_shortcode('ostoolbar', array($this, 'display'));

        $config = Factory::getConfiguration();
        add_action('admin_init', array($config, 'initSettings'));
        add_action('admin_menu', array($this, 'initAdminLinks'));
        //add_action('init', array($this, 'addEditorButton'));
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
     * Return html to display either a list of tutorials or a single tutorial
     *
     * @return string
     */
    public function display()
    {
        ob_start();
        if ($id = Factory::getSanitize()->getInt('id')) {
            Controller::actionTutorial($id);
        } else {
            Controller::actionTutorials(true);
        }
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function addEditorButton()
    {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }

        if (get_user_option('rich_editing') == 'true') {
            add_filter('mce_external_plugins', array($this, 'loadPlugin'));
            add_filter('mce_buttons', array($this, 'registerButton'));
        }
    }

    public function loadPlugin($plugin_array)
    {
        $plug                             = plugins_url('mce/editor_plugin.js', __FILE__);
        $plugin_array['ostoolbar_plugin'] = $plug;

        return $plugin_array;
    }

    public function registerButton($buttons)
    {
        $b[] = 'separator';
        $b[] = 'ostoolbar_plugin_button';
        if (is_array($buttons) && !empty($buttons)) {
            $b = array_merge($buttons, $b);
        }

        return $b;
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

    public function startListener()
    {
        /** @var Model\Help $model */
        $model = Factory::getModel('Help');
        $model->listen();
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
                $app->getUrl(OSTOOLBAR_ASSETS . '/css/ui-lightness/jquery-ui.css')
            );
            wp_enqueue_style('ostoolbar-jquery-ui');

            wp_register_script('ostoolbar-jquery-ui', $app->getUrl(OSTOOLBAR_ASSETS . '/js/jquery-ui.js'));
            wp_enqueue_script('ostoolbar-jquery-ui');

            // Add configuration scripts/css
            wp_register_style('ostoolbar-configuration', $app->getUrl(OSTOOLBAR_ASSETS . '/css/configuration.css'));
            wp_enqueue_style('ostoolbar-configuration');

            wp_register_script('ostoolbar-configuration', $app->getUrl(OSTOOLBAR_ASSETS . '/js/configuration.js'));
            wp_enqueue_script('ostoolbar-configuration');
        }
    }

    public function loadJs()
    {
        $height = get_option('popup_height');
        $width  = get_option('popup_width');

        if (!$height) {
            $height = 500;
        }
        if (!$width) {
            $width = 500;
        }

        $js = <<<JS
<script type='text/javascript'>
	function ostoolbar_popup(address, title, params)
	{
		if (params == null) {
			params = {};
		}

		if (!params.height) {
			params.height = $height;
		}

		if (!params.width) {
			params.width = $width;
		}

		var attr = [];
		for (key in params) {
			attr.push(key+'='+params[key]);
		}
		attr = attr.join(',');

		window.open(address, title, attr);
	}
</script>
JS;
        echo $js;
    }
}
