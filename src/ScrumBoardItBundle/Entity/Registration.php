<?php

namespace ScrumBoardItBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of registration.
 *
 * @author Antony Pradel <antony.pradel@canaltp.fr>
 */
class Registration
{
    /**
     * Username.
     *
     * @var string
     */
    private $username;

    /**
     * Password.
     *
     * @var password
     */
    private $password;

    /**
     * Jira url.
     *
     * @Assert\Url(
     *    message = "L'url '{{ value }}' n'est pas valide",
     * )
     * @var string
     */
    private $jiraUrl;

    /**
     * Username getter.
     *
     * @return string
     */
    function getUsername() {
        return $this->username;
    }

    /**
     * Password getter.
     *
     * @return string
     */
    function getPassword() {
        return $this->password;
    }

    /**
     * Jira url getter.
     *
     * @return string
     */
    function getJiraUrl() {
        return $this->jiraUrl;
    }

    /**
     * Username setter.
     *
     * @param string $username
     *
     * @return self
     */
    function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Password setter.
     *
     * @param string $password
     *
     * @return self
     */
    function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Jira url setter.
     *
     * @param string $jiraUrl
     *
     * @return self
     */
    function setJiraUrl($jiraUrl) {
        $this->jiraUrl = $jiraUrl;

        return $this;
    }
}