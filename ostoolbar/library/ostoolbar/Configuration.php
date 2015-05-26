<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
namespace Ostoolbar;

defined('ABSPATH') or die();

class Configuration
{
    const SETTINGS_PAGE    = 'ostoolbar_settings';
    const SETTINGS_SECTION = 'ostoolbar_settings_section';
    const SETTINGS_GROUP   = 'ostoolbar_settings_group';

    /**
     * Setup the configuration page
     */
    public function initSettings()
    {
        $app = Factory::getApplication();

        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));

        // Section heading and description
        add_settings_section(
            static::SETTINGS_SECTION,
            'Plugin Settings',
            array(
                $this,
                'sectionOut'
            ),
            static::SETTINGS_PAGE
        );

        // API Key field
        add_settings_field(
            'api_key',
            __('API Key', 'ostoolbar'),
            array(
                $this,
                'apikeyField'
            ),
            static::SETTINGS_PAGE,
            static::SETTINGS_SECTION
        );
        register_setting(static::SETTINGS_GROUP, 'api_key');

        // Video selection and ordering
        add_settings_field(
            'videos',
            __('Choose and rearrange videos', 'ostoolbar'),
            array(
                $this,
                'videoField'
            ),
            static::SETTINGS_PAGE,
            static::SETTINGS_SECTION
        );
        register_setting(static::SETTINGS_GROUP, 'videos');

        // Toolbar permissions
        add_settings_field(
            'toolbar_permission',
            __('Choose which users can see videos', 'ostoolbar'),
            array(
                $this,
                'toolbarPermissionField'
            ),
            static::SETTINGS_PAGE,
            static::SETTINGS_SECTION
        );
        register_setting(static::SETTINGS_GROUP, 'toolbar_permission');
    }

    public function enqueueScripts($hook)
    {
        if ($hook = 'settings_page_options-ostoolbar') {
            $app = Factory::getApplication();

            wp_register_style(
                'ostoolbar-jquery-ui',
                $app->getUrl(OSTOOLBAR_ASSETS . '/css/ui-lightness/jquery-ui.css')
            );
            wp_enqueue_style('ostoolbar-jquery-ui');

            wp_register_style('ostoolbar-configuration', $app->getUrl(OSTOOLBAR_ASSETS . '/css/configuration.css'));
            wp_enqueue_style('ostoolbar-configuration');

            wp_register_script('ostoolbar-configuration', $app->getUrl(OSTOOLBAR_ASSETS . '/js/configuration.js'));
            wp_enqueue_script('ostoolbar-configuration');

            wp_register_script('ostoolbar-jquery-ui', $app->getUrl(OSTOOLBAR_ASSETS . '/js/jquery-ui.js'));
            wp_enqueue_script('ostoolbar-jquery-ui');
        }


    }

    /**
     * Display configuration section title and description
     */
    public function sectionOut()
    {
        $response = Request::makeRequest(array('resource' => 'checkapi'));
        if ($response->hasError()) {
            // @TODO: What to do here?
        }
        echo '<p>' . __('Configure the OSToolbar plugin') . '</p>';
    }

    /**
     * Display API Key field
     */
    public function apikeyField()
    {
        $apiKey = get_option('api_key');

        $text = '<input type="text" size="55" name="api_key"'
            . ' value="' . $apiKey . '" />';
        if ($apiKey == '') {
            $text .= __(
                'Enter your API Key from <a href="http://OSTraining.com" target="_blank">OSTraining.com</a>',
                'ostoolbar'
            );
        }
        echo $text;
    }

    /**
     * Toolbar Permissions
     */
    public function toolbarPermissionField()
    {
        $response = Request::makeRequest(array('resource' => 'checkapi'));
        if ($response->hasError()) {
            echo(__('Please enter an API key to use this feature.'));

            return;
        }

        $permission = get_option('toolbar_permission');
        if ($permission == '') {
            $permission = array(
                'editor'      => 0,
                'contributor' => 0,
                'author'      => 0,
                'subscriber'  => 0
            );
        } else {
            $permission = json_decode($permission, true);
        }
        ?>
        <script>
            function array2json(arr) {
                var parts = [];
                var is_list = (Object.prototype.toString.apply(arr) === '[object Array]');

                for (var key in arr) {
                    var value = arr[key];
                    if (typeof value == "object") { //Custom handling for arrays
                        if (is_list) parts.push(array2json(value)); /* :RECURSION: */
                        else parts[key] = array2json(value);
                        /* :RECURSION: */
                    } else {
                        var str = "";
                        if (!is_list) str = '"' + key + '":';

                        //Custom handling for multiple data types
                        if (typeof value == "number") str += value; //Numbers
                        else if (value === false) str += 'false'; //The booleans
                        else if (value === true) str += 'true';
                        else str += '"' + value + '"'; //All other things
                        // @TODO: Is there any more datatype we should be on the lookout for? (Functions?)

                        parts.push(str);
                    }
                }
                var json = parts.join(",");

                if (is_list) return '[' + json + ']';//Return numerical JSON
                return '{' + json + '}';//Return associative JSON
            }
            function UpdatePermission(name) {
                if (document.getElementById("toolbar_permission").value)
                    var test = eval('(' + document.getElementById("toolbar_permission").value + ')');
                else
                    var test = {};
                test[name] = document.getElementById("chk_" + name).checked ? 1 : 0;
                document.getElementById("toolbar_permission").value = array2json(test);

            }
        </script>
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>Administrator</td>
                <td>
                    <input
                        type="checkbox"
                        disabled="disabled"
                        checked="checked"
                        id="chk_administrator"
                        name="administrator"/>
                </td>
            </tr>
            <tr>
                <td>Editors</td>
                <td>
                    <input <?php echo $permission['editor'] ? 'checked="checked"' : ''; ?>
                        type="checkbox"
                        onclick="UpdatePermission('editor')"
                        id="chk_editor"
                        name="editor"/>
                </td>
            </tr>
            <tr>
                <td>Authors</td>
                <td>
                    <input <?php echo $permission['author'] ? 'checked="checked"' : ''; ?>
                        type="checkbox"
                        onclick="UpdatePermission('author')"
                        id="chk_author"
                        name="author"/>
                </td>
            </tr>
            <tr>
                <td>Contributors</td>
                <td>
                    <input <?php echo $permission['contributor'] ? 'checked="checked"' : ''; ?>
                        type="checkbox"
                        onclick="UpdatePermission('contributor')"
                        id="chk_contributor"
                        name="contributor"/>
                </td>
            </tr>
            <tr>
                <td>Subscribers</td>
                <td>
                    <input <?php echo $permission['subscriber'] ? 'checked="checked"' : ''; ?>
                        type="checkbox"
                        onclick="UpdatePermission('subscriber')"
                        id="chk_subscriber"
                        name="subscriber"/>
                </td>
            </tr>
        </table>
        <input
            type="hidden"
            name="toolbar_permission"
            id="toolbar_permission"
            value='<?php echo json_encode($permission); ?>'/>
    <?php
    }

    /**
     * Video selection/ordering field
     */
    public function videoField()
    {
        $data     = array('resource' => 'articles');
        $response = Request::makeRequest($data);
        if ($response->hasError()) {
            echo(__('Please enter an API key to use this feature.'));

            return;
        }
        $list = $response->getBody();

        for ($i = 0; $i < count($list); $i++) {
            $list[$i]->link = 'admin.php?page=ostoolbar&id=' . $list[$i]->id;
        }

        $videos = preg_split("/,/", get_option('videos'), -1, PREG_SPLIT_NO_EMPTY);
        ?>
        <div class='sortable-holder'>
            <div class="sortable-header">Videos not shown to users</div>
            <div class="sortable-header">Videos shown to users</div>
            <div style="clear:both"></div>
            <ul id="sortable1" class="connectedSortable">
                <?php
                foreach ($list as $item) :
                    if ((!$videos || !is_array($videos))
                        || (count($videos) && in_array($item->id, $videos))
                    ) {
                        continue;
                    }
                    ?>
                    <li class="ui-state-default" id="<?php echo($item->id); ?>"><?php echo($item->title); ?></li>
                <?php
                endforeach;
                ?>
            </ul>
            <div class="sortable-divider">
                <?php echo(__("Drag and drop the videos to choose which ones will show to users")); ?>
            </div>
            <?php
            if (count($videos)) {
                $temp = array();
                foreach ($videos as $item) {
                    foreach ($list as $row) {
                        if ($row->id == $item) {
                            $temp[] = $row;
                            break;
                        }
                    }
                }
                $list = $temp;
            }
            ?>
            <ul id="sortable2" class="connectedSortable">
                <?php
                foreach ($list as $item) :
                    if ($videos && is_array($videos) && !in_array($item->id, $videos)) {
                        continue;
                    }
                    ?>
                    <li class="ui-state-highlight" id="<?php echo($item->id); ?>"><?php echo($item->title); ?></li>
                <?php
                endforeach;
                ?>
            </ul>
            <div class="clearfix"></div>
        </div>
        <input
            type='hidden'
            size='55'
            name='videos'
            id="videos"
            value='<?php echo get_option('videos'); ?>'/>
    <?php
    }
}
