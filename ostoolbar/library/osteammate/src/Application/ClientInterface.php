<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\Application;

defined('OSTEAMMATE_LOADED') or die();


interface ClientInterface
{
    public function init();

    /**
     * Get the private identifier, that identifies the client
     *
     * @return string The private identifier
     */
    public function getPrivateIdentifier();

    /**
     * Returns the client's domain
     *
     * @return string The domain
     */
    public function getDomain();

    /**
     * Returns the client's version
     *
     * @return string The version
     */
    public function getVersion();

    /**
     * Returns the current view, creating if not defined.
     *
     * @return ViewableInterface An instance of a view
     */
    public function getView();

    public function isDebug();

    public function isAdmin();
}
