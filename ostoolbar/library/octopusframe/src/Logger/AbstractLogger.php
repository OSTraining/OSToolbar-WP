<?php
/**
 * @package   OctopusFrame
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OctopusFrame\Logger;

use Psr\Log\AbstractLogger as BaseLogger;
use OctopusFrame\Registry\ArrayRegistry;
use OctopusFrame\Factory;
use Exception;

defined('OCTOPUSFRAME_LOADED') or die();


abstract class AbstractLogger extends BaseLogger
{
    protected $basePath;

    protected $filePath;

    public function __construct(ArrayRegistry $options)
    {
        $this->basePath = $options->get('path');
    }

    protected function checkLogFile()
    {
        if (empty($this->filePath)) {
            $container = Factory::getContainer();

            if (!$container->folder->exists($this->basePath)) {
                throw new Exception("Log dir not found");
            }

            $this->filePath = $this->basePath . '/osteammate_' . strtolower($container->platform) . '.log';
        }
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        $this->checkLogFile();

        $container = Factory::getContainer();
        $date      = date('Y-m-d h:i:s');

        $text = "{$date} - {$level}: {$message}";

        if (!empty($context)) {
            $context = json_encode($context);
            $text .= "\nContext: {$context}";
        }

        $text .= "\n-----\n";

        $container->file->write($this->filePath, $text, true);
    }
}
