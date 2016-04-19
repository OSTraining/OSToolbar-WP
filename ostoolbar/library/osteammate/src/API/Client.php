<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\API;

defined('OSTEAMMATE_LOADED') or die();

use OctopusFrame\Registry\ArrayRegistry;
use OctopusFrame\Factory;
use OctopusFrame\Network\HttpTransportCurl;
use OSTeammate\Entity\Pathway;
use OSTeammate\Entity\Course;
use OSTeammate\Entity\Lesson;
use OSTeammate\Entity\Module;
use OSTeammate\Entity\Template;
use OSTeammate\Entity\PackageUpdate;
use OSTeammate\Entity\PathwayUpdate;
use OSTeammate\Entity\ConfigUpdate;
use OSTeammate\Entity\TemplateUpdate;
use OSTeammate\Entity\UpdateChecksum;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\CryptoTestFailedException;
use Defuse\Crypto\Exception\CannotPerformOperationException;
use Exception;
use stdClass;

class Client implements ClientAPIInterface
{
    const SIGNATURE_KEY_SEED     = 'am(voC$yu@wRyidd[eiTh*tyB]Nog=oT@Nef*dEt;gEc<Arv$A';
    const AUTHORIZATION_KEY_SEED = 'ooNk]iCk$bacS#oR+ig[dYd@Ac%Yot?Yev:Aw{Ek*Bo)qUog[A';

    /**
     * API token from parameters
     *
     * @var string
     */
    protected $token = null;

    /**
     * The affiliate link
     *
     * @var string
     */
    protected $affiliateLink = 'https://www.ostraining.com/pricing/';

    /**
     * API target URL
     *
     * @todo      Convert this in a setting with default values
     * @var string
     */
    protected $url = 'https://www.ostraining.com/';

    // @todo      Convert this in a setting with default values
    protected $endpoint = 'api4';

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Send the request to the API
     *
     * @param  string  $task       The task of the API
     * @param  array   $data       An array with the data that will be posted
     *
     * @return stdClass            A generic object
     */
    protected function sendRequest($task, array $data = array())
    {
        $container = Factory::getContainer();

        // Check if the token is empty
        if (empty($this->token)) {
            $container->log->warning('API Token is not set. Task: ' . $task, $data);

            return false;
        }

        $data['client_domain']     = $container->client->getDomain();
        $data['client_platform']   = $container->platform;
        $data['client_version']    = $container->client->getVersion();
        $data['client_private_id'] = $container->client->getPrivateIdentifier();
        $data['token']             = $this->token;

        $options = new ArrayRegistry();
        $options->set('curl.certpath', OCTOPUSFRAME_LIBRARY_PATH . '/Network/cacert.pem');

        $curl     = new HttpTransportCurl($options);
        $url      = "{$this->url}index.php?option=com_osteammate&task={$this->endpoint}.{$task}";

        if ($response = $curl->request('POST', $url, $data)) {
            // Before decode, check the authorization code
            if (!$this->checkResponseAuthorization($response->body)) {
                return false;
            }

            // Ignores the authorization code
            $body = json_decode(substr($response->body, 65));

            // Check the response signature
            if (!$this->checkResponseSignature($body)) {
                return false;
            }

            if (is_object($body) && $body->error == 0) {
                // Set the affiliate link
                if (isset($body->data->affiliate_link)) {
                    $body->data->affiliate_link = trim($body->data->affiliate_link);

                    if (!empty($body->data->affiliate_link)) {
                        $this->affiliateLink = trim($body->data->affiliate_link);
                    }
                }

                return $body->data;
            } else {
                throw new Exception("API error: {$body->message}");
            }
        }

        return false;
    }

    /**
     * Check if the authorization code found in the response is valid
     *
     * @param  string $response The JSON decoded data received from the API
     * @return bool             True if valid
     */
    protected function checkResponseAuthorization($response)
    {
        if (!is_string($response)) {
            return false;
        }

        // Extract authorization code (first 64 chars) and message
        $currentAuthorization = substr($response, 0, 64);
        $message              = substr($response, 65);

        if (empty($currentAuthorization) || empty($message)) {
            return false;
        }

        // Re-calculates the authorization code to compare
        $plainText = $message . self::AUTHORIZATION_KEY_SEED;
        $expectedAuthorization = openssl_digest($plainText, 'sha256');

        if (empty($expectedAuthorization)) {
            return false;
        }

        return $currentAuthorization === $expectedAuthorization;
    }

    /**
     * Check if the signature found in the response is valid
     *
     * @param  object $response The JSON decoded data received from the API
     * @return bool             True if valid
     */
    protected function checkResponseSignature(stdClass $response)
    {
        if (is_object($response) && isset($response->signature)) {
            $signature = $response->signature;
            $key       = $this->getEncryptKey();

            // Decrypt the signature
            try {
                $cipherText = base64_decode($response->signature);
                $original   = Crypto::decrypt($cipherText, $key);
            } catch (CryptoTestFailedException $ex) {
                return null;
            } catch (CannotPerformOperationException $ex) {
                return null;
            }

            // Remove the signature in order to check the message
            unset($response->signature);

            // Calculate a checksum
            $plainText = json_encode($response) . self::SIGNATURE_KEY_SEED;
            $checksum  = openssl_digest($plainText, 'sha256');

            return $original === $checksum;
        }

        Factory::getContainer()->log->warning('Invalid signature of API response', (array)$response);

        return false;
    }

    /**
     * Get API public key for current request
     *
     * @return null|string
     */
    protected function getEncryptKey()
    {
        return Crypto::hexToBin(
            md5($this->token . self::SIGNATURE_KEY_SEED)
        );

        return null;
    }

    /**
     * Returns true if the API is connected, the URL can be reached and we
     * have a valid token.
     *
     * @return boolean True if connected.
     */
    public function isConnected()
    {
        if (empty($this->token)) {
            return false;
        }

        $ping = $this->ping();

        return isset($ping->success) && (bool)$ping->success;
    }

    /**
     * Ping the API to test the connectivity
     *
     * @return boolean True if success
     */
    public function ping()
    {
        return $this->sendRequest('ping');
    }

    /**
     * Returns the current URL used to connect to the API
     *
     * @return string The full URL
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Returns the affiliate link returned by the API
     *
     * @return string The URL
     */
    public function getAffiliateLink()
    {
        return $this->affiliateLink;
    }

    /**
     * Returns a list of training pathways.
     *
     * @return array A list of pathways
     */
    public function getPathwaysList()
    {
        $result = $this->sendRequest('getPathwaysList');

        $pathways = array();

        if (is_object($result) && isset($result->pathways) && !empty($result->pathways)) {
            foreach ($result->pathways as $item) {
                $pathways[] = new Pathway($item);
            }
        }

        return $pathways;
    }

    /**
     * Returns a training pathway instance.
     *
     * @param  int $pathwayId The pathway's id
     *
     * @return Pathway A pathway instance
     */
    public function getPathway($pathwayId)
    {
        $data = array(
            'pathway_id' => $pathwayId
        );
        $result = $this->sendRequest('getPathway', $data);

        if (is_object($result) && isset($result->pathway)) {
            return new Pathway($result->pathway);
        }

        return false;
    }

    /**
     * Returns a list of courses for a specific pathway, and the pathway
     *
     * @param  int $pathwayId The pathway's id
     *
     * @return array A list of courses and a pathway
     */
    public function getCoursesList($pathwayId)
    {
        $data = array(
            'pathway_id' => $pathwayId
        );
        $result = $this->sendRequest('getCoursesList', $data);

        $courses = array();
        $pathway = false;

        if (is_object($result) && isset($result->courses) && !empty($result->courses)) {
            foreach ($result->courses as $item) {
                $courses[] = new Course($item);
            }

            $pathway = new Pathway($result->pathway);
        }

        return array(
            'pathway' => $pathway,
            'courses' => $courses
        );
    }

    /**
     * Returns course and pathway instances.
     *
     * @param  int $pathwayId The pathway ID
     * @param  int $courseId  The course ID
     *
     * @return array Course and Pathway instances
     */
    public function getCourse($pathwayId, $courseId)
    {
        $data = array(
            'pathway_id' => $pathwayId,
            'course_id'  => $courseId
        );
        $result = $this->sendRequest('getCourse', $data);

        $course  = false;
        $pathway = false;

        if (is_object($result) && isset($result->pathway) && isset($result->course)) {
            $pathway = new Pathway($result->pathway);
            $course  = new Course($result->course);
        }

        return array(
            'pathway' => $pathway,
            'course'  => $course
        );
    }

    /**
     * Returns a lesson instance.
     *
     * @param  int $pathwayId A pathway ID
     * @param  int $courseId  A course ID
     * @param  int $lessonId  A lesson ID
     *
     * @return array Instances of Pathway, Course and Lesson. Or a template override.
     */
    public function getLesson($pathwayId, $courseId, $lessonId)
    {
        $data = array(
            'pathway_id' => $pathwayId,
            'course_id'  => $courseId,
            'lesson_id'  => $lessonId
        );
        $result = $this->sendRequest('getLesson', $data);

        $pathway = false;
        $course  = false;
        $lesson  = false;

        if (is_object($result)) {
            if (isset($result->override_content) && $result->override_content) {
                return array(
                    'override' => true,
                    'template' => new Template($result)
                );
            }

            if (isset($result->pathway) && isset($result->course) && isset($result->lesson)) {
                $pathway = new Pathway($result->pathway);
                $course  = new Course($result->course);
                $lesson  = new Lesson($result->lesson);
            }
        }

        return array(
            'pathway' => $pathway,
            'course'  => $course,
            'lesson'  => $lesson
        );
    }

    /**
     * The the checksum hash for the list of pathways, templates and package.
     * A change on any of them means you need to get the updated version.
     *
     * @return UpdateChecksum A list of checksums
     */
    public function getUpdatesChecksum()
    {
        $result = $this->sendRequest('getUpdatesChecksum');

        if (is_object($result)) {
            return new UpdateChecksum($result);
        }

        return false;
    }

    /**
     * Returns a list of the pathways available for the leader and a list of
     * all pathways, allowing to make maintenance procedures.
     *
     * @return PathwayUpdate An instance of pathway update
     */
    public function getPathwaysUpdate()
    {
        $result = $this->sendRequest('getPathwaysUpdate');

        if (is_object($result)) {
            return new PathwayUpdate($result);
        }

        return false;
    }

    /**
     * Returns a list of configurations for the plugin.
     *
     * @return ConfigUpdate An instance of configuration update
     */
    public function getConfigUpdate()
    {
        $result = $this->sendRequest('getConfigUpdate');

        if (is_object($result)) {
            return new ConfigUpdate($result);
        }

        return false;
    }

    /**
     * Returns a list of templates and the respective code, and a list of assets.
     *
     * @return TemplateUpdate The template update data
     */
    public function getTemplatesUpdate()
    {
        $result = $this->sendRequest('getTemplatesUpdate');

        if (is_object($result)) {
            return new TemplateUpdate($result);
        }

        return false;
    }

    /**
     * Returns the package update data
     *
     * @return PackageUpdate The package update data
     */
    public function getPackageUpdate()
    {
        $result = $this->sendRequest('getPackageUpdate');

        if (is_object($result)) {
            return new PackageUpdate($result);
        }

        return false;
    }

    /**
     * Subscribes an email address
     *
     * @param  string $firstName The first name
     * @param  string $lastName  The last name
     * @param  string $email     The email address
     * @param  string $pathwayId The pathway id which user was visiting
     * @param  string $courseId  The course id which user was visiting
     *
     * @return Template          A template to be displayed
     */
    public function subscribe($firstName, $lastName, $email, $pathwayId = 0, $courseId = 0)
    {
        $data = array(
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
            'pathway_id' => $pathwayId,
            'course_id'  => $courseId
        );
        $result = $this->sendRequest('subscribe', $data);

        if (is_object($result)) {
            return new Template($result);
        }

        return false;
    }

    /**
     * Resends the email confirmation for the subscription specified by the
     * hash.
     *
     * @param  string $hash The subscription's hash
     *
     * @return Template     A template to be displayed
     */
    public function resendConfirmation($hash)
    {
        $data = array(
            'hash' => $hash
        );
        $result = $this->sendRequest('resendConfirmation', $data);

        if (is_object($result)) {
            return new Template($result);
        }

        return false;
    }

    /**
     * Resets the subscription specified by the hash.
     *
     * @param  string $hash The subscription's hash
     *
     * @return Template     A template to be displayed
     */
    public function resetSubscription($hash)
    {
        $data = array(
            'hash' => $hash
        );
        $result = $this->sendRequest('resetSubscription', $data);

        if (is_object($result)) {
            return new Template($result);
        }

        return false;
    }

    public function setToken($token)
    {
        if (!empty($token)) {
            $this->token = $token;
        }
    }
}
