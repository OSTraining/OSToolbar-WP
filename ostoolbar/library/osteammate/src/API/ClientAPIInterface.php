<?php
/**
 * @package   OSTeammateJoomla
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace OSTeammate\API;

use OctopusFrame\Registry\RegistrableInterface;

defined('OSTEAMMATE_LOADED') or die();


/**
 * Interface for the OSTeammate Client API
 */
interface ClientAPIInterface
{
    /**
     * Returns true if the API is connected, the URL can be reached and we
     * have a valid token.
     *
     * @return boolean True if connected.
     */
    public function isConnected();

    /**
     * Ping the API to test the connectivity
     *
     * @return boolean True if success
     */
    public function ping();

    /**
     * Returns the current URL used to connect to the API
     *
     * @return string The full URL
     */
    public function getUrl();

    /**
     * Returns a list of training pathways.
     *
     * @return array A list of pathways
     */
    public function getPathwaysList();

    /**
     * Returns a training pathway instance.
     *
     * @param  int $pathwayId The pathway's id
     *
     * @return Pathway A pathway instance
     */
    public function getPathway($pathwayId);

    /**
     * Returns a list of courses for a specific pathway, and the pathway
     *
     * @param  int $pathwayId The pathway's id
     *
     * @return array A list of courses and a pathway
     */
    public function getCoursesList($pathwayId);

    /**
     * Returns course and pathway instances.
     *
     * @param  int $pathwayId The pathway ID
     * @param  int $courseId  The course ID
     *
     * @return array Course and Pathway instances
     */
    public function getCourse($pathwayId, $courseId);

    /**
     * Returns a lesson instance.
     *
     * @param  int $pathwayId A pathway ID
     * @param  int $courseId  A course ID
     * @param  int $lessonId  A lesson ID
     *
     * @return array Instances of Pathway, Course and Lesson
     */
    public function getLesson($pathwayId, $courseId, $lessonId);

    /**
     * The the checksum hash for the list of pathways, templates and package.
     * A change on any of them means you need to get the updated version.
     *
     * @return UpdateChecksum A list of checksums
     */
    public function getUpdatesChecksum();

    /**
     * Returns a list of the pathways available for the leader and a list of
     * all pathways, allowing to make maintenance procedures.
     *
     * @return PathwayUpdate An instance of pathway update
     */
    public function getPathwaysUpdate();

    /**
     * Returns a list of configurations for the plugin.
     *
     * @return ConfigUpdate An instance of configuration update
     */
    public function getConfigUpdate();

    /**
     * Returns a list of templates and the respective code, and a list of assets.
     *
     * @return TemplateUpdate The template update data
     */
    public function getTemplatesUpdate();

    /**
     * Returns the package update data
     *
     * @return PackageUpdate The package update data
     */
    public function getPackageUpdate();

    /**
     * Subscribes an email address
     *
     * @param  string $firstName The first name
     * @param  string $lastName  The last name
     * @param  string $email     The email address
     *
     * @return Template          A template to be displayed
     */
    public function subscribe($firstName, $lastName, $email);

    /**
     * Resends the email confirmation for the subscription specified by the
     * hash.
     *
     * @param  string $hash The subscription's hash
     *
     * @return Template     A template to be displayed
     */
    public function resendConfirmation($hash);

    /**
     * Resets the subscription specified by the hash.
     *
     * @param  string $hash The subscription's hash
     *
     * @return Template     A template to be displayed
     */
    public function resetSubscription($hash);
}
