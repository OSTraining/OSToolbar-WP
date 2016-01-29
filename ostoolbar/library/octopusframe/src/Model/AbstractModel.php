<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Model;

defined('OCTOPUSFRAME_LOADED') or die();

use OctopusFrame\Registry\RegistrableInterface;
use OctopusFrame\Registry\ArrayRegistry;


abstract class AbstractModel implements StatableInterface
{
    /**
     * Array of pathways
     *
     * @var array
     */
    protected $data = array();

    /**
     * The state collection
     *
     * @var RegistrableInterface
     */
    protected $state;

    /**
     * Class constructor
     * @param RegistrableInterface|null $state [description]
     */
    public function __construct(RegistrableInterface $state = null)
    {
        if (null === $state) {
            $state = new ArrayRegistry;
        }

        $this->state = $state;
    }

    /**
     * Sets a state in the model
     *
     * @param string $state The state name
     * @param mixed  $value The state value
     */
    public function setState($state, $value)
    {
        $this->state->set($state, $value);
    }

    /**
     * Returns the state value if exists
     *
     * @param  string $state   The state name
     * @param  mixed  $default The default value
     *
     * @return mixed           The state or default value
     */
    public function getState($state, $default = null)
    {
        return $this->state->get($state, $default);
    }

    /**
     * Returns a list of pathways
     *
     * @return array A list of pathways
     */
    public function getData()
    {
        return $this->data;
    }
}
