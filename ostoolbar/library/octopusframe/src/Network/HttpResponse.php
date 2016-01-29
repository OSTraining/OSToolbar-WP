<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace OctopusFrame\Network;

defined('OCTOPUSFRAME_LOADED') or die;

/**
 * HTTP response data object class.
 */
class HttpResponse
{
    /**
     * @var    integer  The server response code.
     * @since  11.3
     */
    public $code;

    /**
     * @var    array  Response headers.
     * @since  11.3
     */
    public $headers = array();

    /**
     * @var    string  Server response body.
     * @since  11.3
     */
    public $body;
}
