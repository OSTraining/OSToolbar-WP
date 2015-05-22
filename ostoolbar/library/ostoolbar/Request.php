<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
namespace Ostoolbar;

defined('ABSPATH') or die();

abstract class Request
{
    protected static $hostUrl = 'https://www.ostraining.com/';
    public static    $isTrial = false;

    public static function getHostUrl()
    {
        $trial = self::$isTrial ? '_trial' : '';

        $vars = array(
            'option' => 'com_api',
            'v'      => 'wp' . $trial
        );

        return self::$hostUrl . 'index.php?' . http_build_query($vars);
    }

    public static function isTrial()
    {
        static::$isTrial = true;
    }

    public static function makeRequest($data)
    {
        $apikey = get_option('api_key');

        $staticData = array(
            'format' => 'json',
            'key'    => $apikey
        );

        if (!isset($data['app'])) {
            $data['app'] = 'tutorials';
        }
        $data = array_merge($data, $staticData);

        $response = Rest\Request::send(static::getHostUrl(), $data);

        if ($body = $response->getBody()) {
            $response->setBody(json_decode($body));
        }

        if ($response->hasError()) {
            $body = $response->getBody();
            if (isset($body->code)) {
                $response->setErrorCode($body->code);
            }
            if (isset($body->message)) {
                $response->setErrorMsg($body->message);
            }
        }

        return $response;
    }

    public static function filter($text)
    {
        $split  = explode('index.php', static::getHostUrl());
        $ostUrl = $split[0];

        $text = preg_replace('#(href|src)="([^:"]*)("|(?:(?:%20|\s|\+)[^"]*"))#', '$1="' . $ostUrl . '$2$3', $text);

        return $text;
    }
}
