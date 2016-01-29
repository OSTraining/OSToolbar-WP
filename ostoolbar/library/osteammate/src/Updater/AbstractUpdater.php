<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\Updater;

defined('OSTEAMMATE_LOADED') or die();

use OctopusFrame\Factory;
use OSTeammate\Exception\InvalidUpdateType;


abstract class AbstractUpdater implements UpdaterInterface
{
    /**
     * Identify the type of update
     *
     * @var string
     */
    protected $type = '';

    /**
     * The new checksum value received by the update
     *
     * @var string
     */
    protected $newUpdateChecksum = null;

    /**
     * This method should verify the cached checksum and compare with the
     * most updated one.
     *
     * @param int $cacheLifeTime The update cache lifetime
     *
     * @return bool True if has updates
     */
    protected function hasUpdates($updateCacheLifeTime)
    {
        $cache                = Factory::getContainer()->cache;
        $allChecksumsCacheKey = 'update.checksums';

        // Check if the cache of checksums has expired
        $allChecksums = $cache->get($allChecksumsCacheKey);
        if (empty($allChecksums)) {
            // Get new update checksum from the API
            $api          = Factory::getContainer()->api;
            $allChecksums = $api->getUpdatesChecksum();

            if (!is_object($allChecksums)) {
                return false;
            }

            $cache->set($allChecksumsCacheKey, $allChecksums, $updateCacheLifeTime);
        }
        $this->newUpdateChecksum = $allChecksums->{$this->type};

        // Compare the cached checksum with the current one
        $checksumCacheKey = "installed.checksum.{$this->type}";
        $checksum = $cache->get($checksumCacheKey, 0);

        return $checksum !== $this->newUpdateChecksum;
    }

    /**
     * This method will trigger the update routine.
     *
     * @return bool True if updated
     */
    protected function update()
    {
        return false;
    }

    /**
     * Updates the update checksum
     */
    protected function updateChecksumCache()
    {
        $cache            = Factory::getContainer()->cache;
        $checksumCacheKey = "installed.checksum.{$this->type}";

        $cache->set($checksumCacheKey, $this->newUpdateChecksum);
    }

    /**
     * Download a remote file
     *
     * @param  string $source      The source path
     * @param  string $destination The destination path
     */
    protected function downloadFile($source, $destination)
    {
        $ch = curl_init($source);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $rawData = curl_exec($ch);
        curl_close($ch);

        Factory::getContainer()->file->write($destination, $rawData);
    }

    /**
     * This method should verify the cached checksum and compare with the
     * most updated one. If update is found, triggers the update routine.
     *
     * @param int  $cacheLifeTime The update cache lifetime
     * @param bool $force         Force the upload, ignoring the cache
     *
     * @return bool True if was updated
     */
    public function checkUpdate($updateCacheLifeTime = 1, $force = false)
    {
        if ($this->hasUpdates($updateCacheLifeTime) || $force) {
            if ($this->update()) {
                $this->updateChecksumCache();

                return true;
            }
        }

        return false;
    }
}
