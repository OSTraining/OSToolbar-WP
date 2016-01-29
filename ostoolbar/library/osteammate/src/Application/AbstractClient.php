<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\Application;

defined('OSTEAMMATE_LOADED') or die();

use OctopusFrame\TemplateEngine\RenderableInterface;
use OctopusFrame\Access\Controller\AccessibleInterface;
use OctopusFrame\Registry\RegistrableInterface;
use OctopusFrame\Exception\InvalidOption;
use OctopusFrame\Application\AbstractApplication;
use OctopusFrame\Factory;


class AbstractClient extends AbstractApplication implements ClientInterface
{
    /**
     * The client's version
     *
     * @var string
     */
    protected $version;

    /**
     * The client's domain
     *
     * @var string
     */
    protected $domain;

    /**
     * The current view
     *
     * @var ViewableInterface
     */
    protected $view;

    /**
     * The private identifier
     *
     * @var string
     */
    protected $privateIdentifier;

    /**
     * Class constructor
     */
    public function __construct()
    {

    }

    public function init()
    {
        $this->version           = $this->getVersion();
        $this->domain            = $this->getDomain();
        $this->privateIdentifier = $this->getPrivateIdentifier();
    }

    /**
     * Get the private identifier, that identifies the client
     *
     * @return string The private identifier
     */
    public function getPrivateIdentifier()
    {
        if (empty($this->privateIdentifier)) {
            $userId = Factory::getContainer()->user->getId();
            $domain = $this->getDomain();

            $this->privateIdentifier = $userId . '@' . $domain;
        }

        return $this->privateIdentifier;
    }

    /**
     * Returns the client's domain
     *
     * @return string The domain
     */
    public function getDomain()
    {
        if (empty($this->domain)) {
            $this->domain = @$_SERVER['HTTP_HOST'];
        }

        return $this->domain;
    }

    /**
     * Returns the client's version
     *
     * @return string The version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Returns the current view, creating if not defined.
     *
     * @return ViewableInterface An instance of a view
     */
    public function getView()
    {
        if (empty($this->view)) {
            $container     = Factory::getContainer();
            $viewName      = $container->router->getViewName();
            $viewClassName = 'OSTeammate\\View\\' . ucfirst($viewName);

            $this->view = new $viewClassName($container->templateEngine);
        }

        return $this->view;
    }

    public function isDebug()
    {
        return false;
    }

    public function isAdmin()
    {
        return false;
    }

    /**
     * Returns the current theme, based on the url
     *
     * @return string The theme name
     */
    public function getTheme()
    {
        return 'default';
    }
}
