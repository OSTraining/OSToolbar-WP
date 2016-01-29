<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Router;

use OctopusFrame\Router\RoutableInterface;
use OctopusFrame\Factory;

defined('OCTOPUSFRAME_LOADED') or die();


abstract class AbstractRouter implements RoutableInterface
{
    protected $urlParamsPrefix = 'ostm_';

    protected $baseURL = '';

    public function getViewName()
    {
        $viewName = $this->getParam('view', 'pathways');

        // Check the access and redirect to access denied, if required
        $context = "view.{$viewName}";
        if (!Factory::getContainer()->access->hasAccess($context)) {
            $viewName = 'AccessDenied';
        }

        return $viewName;
    }

    public function getParamsList($onlyPrefixed = true)
    {
        $params = array();

        foreach ($_GET as $param => $value) {
            if ($onlyPrefixed) {
                $regex = '/^' . $this->urlParamsPrefix . '/';

                if (preg_match($regex, $param)) {
                    $param = preg_replace($regex, '', $param);

                    $params[$param] = $value;
                }
            } else {
                $params[$param] = $value;
            }
        }

        return $params;
    }

    public function setParam($param, $value, $prefixed = true)
    {
        $key = $param;

        if ($prefixed) {
            $key = $this->urlParamsPrefix . $key;
        }

        $_GET[$key] = (string)$value;
    }

    public function getParam($param, $default = null, $prefixed = true)
    {
        if ($prefixed) {
            $param = $this->urlParamsPrefix . $param;
        }

        if (array_key_exists($param, $_GET)) {
            return $_GET[$param];
        }

        // Return the default value
        return $default;
    }

    public function route($data)
    {
        parse_str($_SERVER['QUERY_STRING'], $params);

        // Remove prefixed params - cleanup
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                if (preg_match('/^' . $this->urlParamsPrefix . '/', $key)) {
                    unset($params[$key]);
                }
            }
        }

        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $params[$this->urlParamsPrefix . $key] = $value;
            }
        }

        $query = http_build_query($params);

        return !empty($query) ? '?' . $query : '';
    }
}
