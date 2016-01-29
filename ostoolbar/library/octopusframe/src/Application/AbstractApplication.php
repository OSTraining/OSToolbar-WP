<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Application;

use OctopusFrame\TemplateEngine\RenderableInterface;
use OctopusFrame\Access\Controller\AccessibleInterface;
use OctopusFrame\Registry\RegistrableInterface;
use UnexpectedValueException;

defined('OSTEAMMATE_LOADED') or die();


class AbstractApplication
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
     *
     * @param RegistrableInterface  $options  An registry of options
     */
    public function __construct()
    {
        $requiredOptions = array(
            'version',
            'domain',
            'privateIdentifier'
        );

        foreach ($requiredOptions as $option) {
            $this->$option = $options->get($option, null);
            if ($this->$option === null) {
                throw new UnexpectedValueException('Invalid option: ' . $option);
            }
        }
    }

    /**
     * Get the private identifier, that identifies the client
     *
     * @return [type] [description]
     */
    public function getPrivateIdentifier()
    {
        return $this->privateIdentifier;
    }

    /**
     * Returns the client's domain
     *
     * @return string The domain
     */
    public function getDomain()
    {
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
}
