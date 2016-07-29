<?php

namespace ScrumBoardItBundle\Entity\Mapping;

use Doctrine\ORM\Mapping as ORM;

/**
 * Favorites.
 *
 * @ORM\Table(name="favorites")
 * @ORM\Entity
 */
class Favorites
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer", unique=true)
     */
    private $userId;

    /**
     * @var array
     *
     * @ORM\Column(name="jira", type="array")
     */
    private $jira = array();

    /**
     * @var array
     *
     * @ORM\Column(name="github", type="array")
     */
    private $github = array();

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId.
     *
     * @param int $userId
     *
     * @return Favorites
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set jira.
     *
     * @param array $jira
     *
     * @return Favorites
     */
    public function setJira($jira)
    {
        $this->jira = $jira;

        return $this;
    }

    /**
     * Get jira.
     *
     * @return array
     */
    public function getJira()
    {
        return $this->jira;
    }

    /**
     * Set github.
     *
     * @param array $github
     *
     * @return Favorites
     */
    public function setGithub($github)
    {
        $this->github = $github;

        return $this;
    }

    /**
     * Get github.
     *
     * @return array
     */
    public function getGithub()
    {
        return $this->github;
    }
}
