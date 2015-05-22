<?php
/**
 * @package    OSToolbar-WP
 * @contact    www.alledia.com, support@alledia.com
 * @copyright  2015 Alledia.com, All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
namespace Ostoolbar\Model;

use Ostoolbar\Cache;
use Ostoolbar\Model;
use Ostoolbar\Request;

class Help extends Model
{
    public function listen()
    {
        $uri          = $_SERVER['REQUEST_URI'];
        $split        = explode("/", $uri);
        $last         = count($split) - 1;
        $adminLink   = $split[$last];
        $helpArticles = $this->getList();

        if ($msg = $this->getError()) {
            if (strpos($msg, 'API Key Not Found') !== false) {
                $msg .= ". Fix this <a href='options-general.php?page=options-ostoolbar'>here</a>.";
            }
            echo $msg;

            return;
        }

        if ($article = $this->search($adminLink, $helpArticles)) {
            $link = 'admin.php?page=ostoolbar&help=' . $article->id;

            echo '<a href="javascript:ostoolbar_popup(\'' . $link . '\', \'' . $article->title . '\');">'
                . $article->title
                . '</a>';
        }
    }

    private function search($adminLink, $helpArticles)
    {
        $adminUri = $this->parseUri($adminLink);

        for ($i = 0; $i < count($helpArticles); $i++) {
            $h      = $helpArticles[$i];
            $parsed = $this->parseUri($h->url);
            if ($h->url_exact) {
                if ($parsed['hash'] == $adminUri['hash']) {
                    return $h;
                }
            } elseif ($parsed['page'] == $adminUri['page']) {
                if (!$parsed['vars']) {
                    return $h;
                }
                // Compare keys
                $adminKeys  = array_keys($adminUri['vars']);
                $parsedKeys = array_keys($parsed['vars']);

                $intersect = array_intersect($parsedKeys, $adminKeys);
                if (count($intersect) == count($parsedKeys)) {
                    $compare = array();
                    foreach ($intersect as $index) {
                        $compare[$index] = $adminUri['vars'][$index];
                    }
                    ksort($compare);

                    if (md5(serialize($compare)) == md5(serialize($parsed['vars']))) {
                        return $h;
                    }
                }

            }
        }

        return false;

    }

    protected function parseUri($uri)
    {
        list($page, $query) = explode("?", $uri);
        $vars = array();
        if ($query) {
            parse_str($query, $vars);
        }

        ksort($vars);

        $hash = $page;
        if ($vars) {
            $hash .= md5(serialize($vars));
        }

        return compact('page', 'vars', 'hash');
    }

    public function getData()
    {
        $data = $this->getList();
        for ($i = 0; $i < count($data); $i++) {
            $d = $data[$i];
            if ($d->id == $this->getState('id')) {
                $d->introtext = Request::filter($d->introtext);
                $d->fulltext  = Request::filter($d->fulltext);

                return $d;
            }
        }

        return null;
    }

    public function getList()
    {
        $data = Cache::callback($this, 'fetchList', array(), null, true);

        return $data;
    }

    public function fetchList()
    {

        $data = array('resource' => 'helpArticles');

        $response = Request::makeRequest($data);

        if ($response->hasError()) :
            $this->setError(
                __('OSToolbar Error') . ':  '
                . $response->getErrorMsg()
                . ' (' . __('Code') . ' ' . $response->getErrorCode() . ')'
            );

            return false;
        endif;

        $list = $response->getBody();

        for ($i = 0; $i < count($list); $i++) :
            $list[$i]->link = 'admin.php?page=ostoolbar&id=' . $list[$i]->id;
        endfor;

        return $list;
    }
}
