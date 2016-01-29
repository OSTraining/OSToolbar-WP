<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Platform\Joomla;

use OctopusFrame\Registry\ArrayRegistry;
use Psr\Log\LoggerInterface;
use JLog;
use JText;

defined('OCTOPUSFRAME_LOADED') or die();

 jimport('joomla.log.log');


class Logger implements LoggerInterface
{
    protected $name;

    public function __construct(ArrayRegistry $options)
    {
        $this->name = $options->get('name');

        JLog::addLogger(
            array('text_file' => $this->name . '.php'),
            JLog::ALL,
            array($this->name)
        );
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = array())
    {
        JLog::add(JText::_($message), JLog::EMERGENCY, $this->name);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = array())
    {
        JLog::add(JText::_($message), JLog::ALERT, $this->name);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array())
    {
        JLog::add(JText::_($message), JLog::CRITICAL, $this->name);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array())
    {
        JLog::add(JText::_($message), JLog::ERROR, $this->name);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = array())
    {
        JLog::add(JText::_($message), JLog::WARNING, $this->name);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = array())
    {
        JLog::add(JText::_($message), JLog::NOTICE, $this->name);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array())
    {
        JLog::add(JText::_($message), JLog::INFO, $this->name);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function debug($message, array $context = array())
    {
        JLog::add(JText::_($message), JLog::DEBUG, $this->name);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        JLog::add(JText::_($message), $level, $this->name);
    }
}
