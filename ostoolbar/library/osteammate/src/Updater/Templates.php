<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\Updater;

use OctopusFrame\Factory;
use OSTeammate\Entity\TemplateUpdate;

defined('OSTEAMMATE_LOADED') or die();


class Templates extends AbstractUpdater
{
    /**
     * Identify the type of update
     *
     * @var string
     */
    protected $type = 'templates';

    /**
     * This method will trigger the update routine.
     *
     * @return bool True if updated
     */
    protected function update()
    {
        $api = Factory::getContainer()->api;

        // Get the most updated templates
        if ($updates = $api->getTemplatesUpdate()) {
            $this->saveTemplates($updates);
            $this->saveAssets($updates);

            return true;
        }

        return false;
    }

    /**
     * Saves the templates files
     *
     * @param  TemplateUpdate|false  $data The update data with templates list
     */
    protected function saveTemplates($data)
    {
        $container = Factory::getContainer();
        $path      = $container->templateEngine->getTemplatesPath();
        $extension = $container->templateEngine->getTemplateFileExtension();

        if (!empty($data->templates)) {
            foreach ($data->templates as $template) {
                $filePath = $path . '/' . $template->name . $extension;
                $container->file->write($filePath, $template->content);
            }
        }
    }

    /**
     * Saves the assets
     *
     * @param  TemplateUpdate  $data The update data with assets list
     */
    protected function saveAssets(TemplateUpdate $data)
    {
        $container = Factory::getContainer();

        // Download the assets
        $assetTypes = array('css', 'js', 'img');
        foreach ($assetTypes as $type) {
            if (isset($data->$type) && !empty($data->$type)) {
                $list = $data->$type;
                foreach ($list as $asset) {
                    // Check the url type: local/remote
                    // @TODO: Maybe it would be better just to strip them from the API response
                    if (preg_match('#^(\/\/|http[s]?\:\/\/)#i', $asset)) {
                        // It is remote, let's ignore
                        continue;
                    }

                    $source      = $data->assetsBaseURL . preg_replace('#^/#', '', $asset);
                    $destination = $container->templateEngine->getAssetsPath() . '/' . $type . '/' . basename($asset);

                    // Check if the destination directory exists
                    $dir = str_replace(basename($destination), '', $destination);
                    if (!$container->folder->exists($dir)) {
                        $container->folder->create($dir);
                    }

                    $this->downloadFile($source, $destination);
                }
            }
        }
    }
}
