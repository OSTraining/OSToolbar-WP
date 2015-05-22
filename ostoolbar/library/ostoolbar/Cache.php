<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
namespace Ostoolbar;

defined('ABSPATH') or die();

class Cache
{
    public static $cacheGroup = 'ostoolbar';

    const DAY      = 86400;
    const HALF_DAY = 43200;
    const HOUR     = 3600;
    const MINUTE   = 60;

    /**
     * @param object $object
     * @param string $method
     * @param array  $args
     * @param null   $cacheLifetime
     *
     * @return mixed
     */
    public static function callback($object, $method, $args = array(), $cacheLifetime = null)
    {
        $cache = Factory::get_cache_storage();

        if (!$cacheLifetime) {
            $cacheLifetime = static::HALF_DAY;
        }
        $cache->setLifetime($cacheLifetime);

        $response = Request::make_request(array('resource' => 'checkapi'));
        if ($response->has_error()) {
            static::$cacheGroup = static::$cacheGroup . '_trial';
            Request::is_trial();
        }

        $callback = array($object, $method);
        $cacheId = static::getCacheId($callback, $args);

        $data = $cache->get($cacheId, static::$cacheGroup);

        if ($data) {
            $data     = unserialize($data);
            $response = Request::make_request(array('resource' => 'lastupdate'));
            if (!$response->has_error()) {
                $last_update = strtotime($response->get_body());
                if (is_array($data)) {
                    if ((count($data) && strtotime($data[0]->last_update_date) < $last_update)
                        || count($data) == 0
                    ) {
                        $cache->remove($cacheId, static::$cacheGroup);
                        $data = call_user_func_array($callback, $args);

                        if ($data !== false) {
                            $cached = trim(serialize($data));
                            $cache->store($cacheId, static::$cacheGroup, $cached);
                        }

                        return $data;
                    }
                }
            }

            return $data;

        } else {
            $data = call_user_func_array($callback, $args);

            if ($data !== false) {
                $cached = trim(serialize($data));
                $cache->store($cacheId, static::$cacheGroup, $cached);
            }

            return $data;
        }
    }

    /**
     * @param mixed $callback
     * @param mixed $args
     *
     * @return string
     */
    protected static function getCacheId($callback, $args)
    {
        if (is_array($callback) && is_object($callback[0])) {
            $vars        = get_object_vars($callback[0]);
            $vars[]      = strtolower(get_class($callback[0]));
            $callback[0] = $vars;
        }

        return md5(serialize(array($callback, $args, static::$cacheGroup)));
    }
}
