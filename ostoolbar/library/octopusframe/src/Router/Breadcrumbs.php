<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @copyright Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace OctopusFrame\Router;

use stdClass;

defined('OCTOPUSFRAME_LOADED') or die();

/**
 * Very minimum implementation of Pathway class from Joomla.
 * Class to maintain a pathway.
 * The user's navigated path within the application.
 *
 */
class Breadcrumbs
{
    /**
     * @var    array  Array to hold the pathway item objects
     * @since  1.5
     * @deprecated  4.0  Will convert to $pathway
     */
    protected $_pathway = array();

    /**
     * @var    integer  Integer number of items in the pathway
     * @since  1.5
     * @deprecated  4.0  Will convert to $count
     */
    protected $_count = 0;

    /**
     * @var    array  Pathway instance
     * @since  1.7
     */
    protected static $instance = null;

    /**
     * Class constructor
     *
     * @param   array  $options  The class options.
     *
     * @since   1.5
     */
    public function __construct($options = array())
    {
    }

    /**
     * Returns a Pathway object
     *
     * @param   string  $client   The name of the client
     * @param   array   $options  An associative array of options
     *
     * @return  Pathway  A Pathway object.
     *
     * @since   1.5
     */
    public static function getInstance($options = array())
    {
        if (empty(static::$instance))
        {
            static::$instance = new static($options);
        }

        return static::$instance;
    }

    /**
     * Return the Pathway items array
     *
     * @return  array  Array of pathway items
     *
     * @since   1.5
     */
    public function getPathway()
    {
        $pw = $this->_pathway;

        // Use array_values to reset the array keys numerically
        return array_values($pw);
    }

    /**
     * Set the Pathway items array.
     *
     * @param   array  $pathway  An array of pathway objects.
     *
     * @return  array  The previous pathway data.
     *
     * @since   1.5
     */
    public function setPathway($pathway)
    {
        $oldPathway = $this->_pathway;

        // Set the new pathway.
        $this->_pathway = array_values((array) $pathway);

        return array_values($oldPathway);
    }

    /**
     * Create and return an array of the pathway names.
     *
     * @return  array  Array of names of pathway items
     *
     * @since   1.5
     */
    public function getPathwayNames()
    {
        $names = array();

        // Build the names array using just the names of each pathway item
        foreach ($this->_pathway as $item)
        {
            $names[] = $item->name;
        }

        // Use array_values to reset the array keys numerically
        return array_values($names);
    }

    /**
     * Create and add an item to the pathway.
     *
     * @param   string  $name  The name of the item.
     * @param   string  $link  The link to the item.
     *
     * @return  boolean  True on success
     *
     * @since   1.5
     */
    public function addItem($name, $link = '')
    {
        $ret = false;

        if ($this->_pathway[] = $this->makeItem($name, $link))
        {
            $ret = true;
            $this->_count++;
        }

        return $ret;
    }

    /**
     * Set item name.
     *
     * @param   integer  $id    The id of the item on which to set the name.
     * @param   string   $name  The name to set.
     *
     * @return  boolean  True on success
     *
     * @since   1.5
     */
    public function setItemName($id, $name)
    {
        $ret = false;

        if (isset($this->_pathway[$id]))
        {
            $this->_pathway[$id]->name = $name;
            $ret = true;
        }

        return $ret;
    }

    /**
     * Create and return a new pathway object.
     *
     * @param   string  $name  Name of the item
     * @param   string  $link  Link to the item
     *
     * @return  Pathway  Pathway item object
     *
     * @since   1.5
     * @deprecated  4.0  Use makeItem() instead
     */
    protected function _makeItem($name, $link)
    {
        return $this->makeItem($name, $link);
    }

    /**
     * Create and return a new pathway object.
     *
     * @param   string  $name  Name of the item
     * @param   string  $link  Link to the item
     *
     * @return  Pathway  Pathway item object
     *
     * @since   3.1
     */
    protected function makeItem($name, $link)
    {
        $item = new stdClass;
        $item->name = html_entity_decode($name, ENT_COMPAT, 'UTF-8');
        $item->link = $link;

        return $item;
    }
}
