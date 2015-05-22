<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
namespace Ostoolbar;

defined('ABSPATH') or die();

class Sanitize
{
    const HASH_DEFAULT = '$_GET';
    const HASH_GET     = '$_GET';
    const HASH_METHOD  = 'REQUEST_METHOD';
    const HASH_POST    = '$_POST';
    const HASH_REQUEST = '$_REQUEST';
    const HASH_SERVER  = '$_SERVER';

    public function getHash($hash = self::HASH_DEFAULT)
    {
        if ($hash == static::HASH_METHOD) {
            $hash = '$_' . $_SERVER[$hash];
        }

        if (isset($$hash) && is_array($$hash)) {
            return $$hash;
        }

        return array();
    }

    /**
     * @param string   $key
     * @param string   $hash
     * @param callable $function
     *
     * @return mixed|null
     */
    public function get($key, $hash = self::HASH_DEFAULT, $function = null)
    {
        $source = $this->getHash($hash);

        $value = null;
        if (isset($source[$key])) {
            $value = $source[$key];

            if (is_string($function) && function_exists($function)) {
                $value = $function($value);

            } elseif (is_callable($function)) {
                $value = call_user_func($function, $value);
            }
        }

        return $value;
    }

    public function getEmail($key, $hash = self::HASH_DEFAULT)
    {
        return $this->get($key, 'sanitize_email', $hash);
    }

    public function getFileName($key, $hash = self::HASH_DEFAULT)
    {
        return $this->get($key, 'sanitize_file_name', $hash);
    }

    public function getHtmlClass($key, $hash = self::HASH_DEFAULT)
    {
        return $this->get($key, 'sanitize_html_class', $hash);
    }

    public function getInt($key, $hash = self::HASH_DEFAULT)
    {
        return $this->get($key, 'intval', $hash = null);
    }

    public function getKey($key, $hash = self::HASH_DEFAULT)
    {
        return $this->get($key, 'sanitize_key', $hash);
    }

    public function getMeta($key, $hash = self::HASH_DEFAULT)
    {
        return $this->get($key, 'sanitize_meta', $hash);
    }

    public function getMimeType($key, $hash = self::HASH_DEFAULT)
    {
        return $this->get($key, 'sanitize_mime_type', $hash);
    }

    public function getOption($key, $hash = self::HASH_DEFAULT)
    {
        return $this->get($key, 'sanitize_option', $hash);
    }

    public function getSqlOrderby($key, $hash = self::HASH_DEFAULT)
    {
        return $this->get($key, 'sanitize_sql_orderby', $hash);
    }

    public function getTextField($key, $hash = self::HASH_DEFAULT)
    {
        return $this->get($key, 'sanitize_text_field', $hash);
    }

    public function getTitle($key, $hash = self::HASH_DEFAULT)
    {
        return $this->get($key, 'sanitize_title', $hash);
    }

    public function getTitleForQuery($key, $hash = self::HASH_DEFAULT)
    {
        return $this->get($key, 'sanitize_title_for_query', $hash);
    }

    public function getTitleWithDashes($key, $hash = self::HASH_DEFAULT)
    {
        return $this->get($key, 'sanitize_title_with_dashes', $hash);
    }

    public function getUser($key, $hash = self::HASH_DEFAULT)
    {
        return $this->get($key, 'sanitize_user', $hash);
    }
}
