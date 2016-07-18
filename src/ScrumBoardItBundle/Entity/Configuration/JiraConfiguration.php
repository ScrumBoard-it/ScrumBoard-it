<?php

namespace ScrumBoardItBundle\Entity\Configuration;

use Doctrine\ORM\Mapping as ORM;

/**
 * JiraConfiguration.
 *
 * @ORM\Table(name="configuration\jira_configuration")
 */
class JiraConfiguration
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
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="complexity_field", type="string", length=255, nullable=true)
     */
    private $complexityField;

    /**
     * @var string
     *
     * @ORM\Column(name="printed_tag", type="string", length=255)
     */
    private $printedTag;

    /**
     * @var string
     *
     * @ORM\Column(name="businnessvalue_field", type="string", length=255, nullable=true)
     */
    private $businnessvalueField;

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
     * @return JiraConfiguration
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
     * Set url.
     *
     * @param string $url
     *
     * @return JiraConfiguration
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set complexityField.
     *
     * @param string $complexityField
     *
     * @return JiraConfiguration
     */
    public function setComplexityField($complexityField)
    {
        $this->complexityField = $complexityField;

        return $this;
    }

    /**
     * Get complexityField.
     *
     * @return string
     */
    public function getComplexityField()
    {
        return $this->complexityField;
    }

    /**
     * Set printedTag.
     *
     * @param string $printedTag
     *
     * @return JiraConfiguration
     */
    public function setPrintedTag($printedTag)
    {
        $this->printedTag = $printedTag;

        return $this;
    }

    /**
     * Get printedTag.
     *
     * @return string
     */
    public function getPrintedTag()
    {
        return $this->printedTag;
    }

    /**
     * Set businnessvalueField.
     *
     * @param string $businnessvalueField
     *
     * @return JiraConfiguration
     */
    public function setBusinnessvalueField($businnessvalueField)
    {
        $this->businnessvalueField = $businnessvalueField;

        return $this;
    }

    /**
     * Get businnessvalueField.
     *
     * @return string
     */
    public function getBusinnessvalueField()
    {
        return $this->businnessvalueField;
    }
}
