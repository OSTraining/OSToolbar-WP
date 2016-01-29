<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\Updater;

defined('OSTEAMMATE_LOADED') or die();


interface UpdaterInterface
{
    /**
     * This method should verify the cached checksum and compare with the
     * most updated one. If update is found, triggers the update routine.
     *
     * @param int  $cacheLifeTime The update cache lifetime
     * @param bool $force         Force the upload, ignoring the cache
     *
     * @return bool True if was updated
     */
    public function checkUpdate($updateCacheLifeTime = 1, $force = false);
}
