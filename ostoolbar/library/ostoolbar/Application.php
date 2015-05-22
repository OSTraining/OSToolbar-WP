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
    public function init()
    {
        add_shortcode('ostoolbar', array('\Ostoolbar\Application', 'display'));

        get_role('administrator')->add_cap('see_videos');
        $text = get_option('toolbar_permission');
        if ($text == '') {
            $text = json_encode(
                array(
                    "editor"      => 0,
                    "contributor" => 0,
                    "author"      => 0,
                    "subscriber"  => 0
                )
            );
        }
        $permission = json_decode($text, true);
        foreach ($permission as $key => $value) {
            if ($value) {
                get_role($key)->add_cap('see_videos');
            } else {
                get_role($key)->remove_cap('see_videos');
            }
        }

        $config = Factory::getConfiguration();
        add_action('admin_init', array($config, 'initSettings'));
        add_action('admin_menu', array($this, 'initAdminLinks'));
        add_action('admin_head', array($this, 'loadJs'));

        if (isset($_GET['page']) && $_GET['page'] == 'ostoolbar') {
            add_action('admin_notices', array($this, 'apikeyCheck'));
        }
        add_action('init', array($this, 'addEditorButton'));
    }

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

    public function apikeyCheck()
    {
        $api_key = get_option('api_key');
        if (!$api_key) {
            // @TODO: Determine what to do here
        }
    }

    public function initAdminLinks()
    {
        $controller = Factory::getController();

        $title = get_option('toolbar_text') == '' ? 'OSToolbar' : get_option('toolbar_text');
        $icon  = get_option('toolbar_icon') == '' ? 'ost_icon.png' : get_option('toolbar_icon');

        wp_deregister_style('ostoolbar_menu_css');
        wp_register_style('ostoolbar_menu_css', plugins_url('ostoolbar/assets/css/menu.php?icon=' . $icon));
        wp_enqueue_style('ostoolbar_menu_css');

        add_object_page(
            $title,
            $title,
            'see_videos',
            'ostoolbar',
            array(
                $controller,
                'actionTutorials'
            ),
            ''
        );

        add_options_page(
            __($title . ' Configuration', 'ostoolbar'),
            $title,
            'manage_options',
            'options-ostoolbar',
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
