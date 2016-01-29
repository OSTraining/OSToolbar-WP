<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\View;

defined('OSTEAMMATE_LOADED') or die();

use OctopusFrame\Factory;
use OSTeammate\Entity\Template;
use Exception;


class AbstractOverridableView extends AbstractView
{
    /**
     * The content that will override the view's output
     *
     * @var string
     */
    protected $contentOverride = null;

    /**
     * Check if we have a form submission to route to the API
     *
     * @param  array &$data An array with data for the template
     */
    protected function checkFormSubmissionAndRouteToAPI(array &$data)
    {
        // Is it a form submission?
        if (strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
            // Look for an API context field
            if (isset($_POST['api_context'])) {
                $apiContext = preg_replace('#[^a-z0-9_.]+#i', '', $_POST['api_context']);

                if (!empty($apiContext)) {
                    // Get any additional POST data and pass through the API
                    $sanitizedData = array();
                    $protectedFields = array(
                        'api_context',
                        'token',
                        'client_version',
                        'client_domain',
                        'client_private_id'
                    );

                    foreach ($_POST as $field => $value) {
                        if (!in_array($field, $protectedFields)) {
                            $value = preg_replace('#[\{\}%\[\]\|=]+#i', '', $value);
                            $value = trim(strip_tags($value));
                            $sanitizedData[$field] = $value;
                        }
                    }

                    $methodName    = preg_replace('#[^a-z0-9]#i', '', $apiContext);

                    $api = Factory::getContainer()->api;
                    if (method_exists($api, $methodName)) {
                        switch ($methodName)
                        {
                            case 'subscribe':
                                $result = $api->subscribe(
                                    $sanitizedData['first_name'],
                                    $sanitizedData['last_name'],
                                    $sanitizedData['email'],
                                    $sanitizedData['pathway_id'],
                                    $sanitizedData['course_id']
                                );
                                break;
                            case 'resendConfirmation':
                                $result = $api->resendConfirmation($sanitizedData['hash']);
                                break;
                            case 'resetSubscription':
                                $result = $api->resetSubscription($sanitizedData['hash']);
                                break;
                        }

                        $data = array(
                            'override' => true,
                            'template' => $result
                        );
                    } else {
                        throw new Exception('Invalid API Context');
                    }
                } else {
                    throw new Exception('Invalid API Context');
                }
            }
        }
    }

    /**
     * Returns the data to be used to render the template. This method should be overriden by
     * every child class
     *
     * @param array $data An initial data
     *
     * @return array The data for templates
     */
    protected function getData(array $data = array())
    {
        $this->checkFormSubmissionAndRouteToAPI($data);

        if (!isset($data['theme'])) {
            $data['theme'] = Factory::getContainer()->client->getTheme();
        }

        return $data;
    }

    /**
     * Returns the output of the view, rendered.
     *
     * @return string The rendered output
     */
    public function getOutput()
    {
        $data = $this->getData();

        if (isset($data['override']) && $data['override']) {
            $this->templateName = $data['template']->name;
        }

        return $this->templateEngine->render($this->templateName, $data);
    }
}
