<?php

namespace ScrumBoardItBundle\Entity\Profile;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * JiraProfileEntity.
 */
class JiraProfileEntity
{
    /**
     * @var string
     *
     * @Assert\Regex(
     *     pattern="/\S*\/$/",
     *     match=true,
     *     message="L'url doit finir par '/'"
     * )
     * @Assert\Url(
     *    protocols = {"http"},
     *    message = "Url invalide"
     * )
     */
    private $url;

    /**
     * @var string
     */
    private $printedTag;

    /**
     * @var string
     */
    private $complexityField;

    /**
     * @var string
     */
    private $businnessValueField;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrintedTag()
    {
        return $this->printedTag;
    }

    /**
     * @param string $printedTag
     *
     * @return self
     */
    public function setPrintedTag($printedTag)
    {
        $this->printedTag = $printedTag;

        return $this;
    }

    /**
     * @return string
     */
    public function getComplexityField()
    {
        return $this->complexityField;
    }

    /**
     * @param string $complexityField
     *
     * @return self
     */
    public function setComplexityField($complexityField)
    {
        $this->complexityField = $complexityField;

        return $this;
    }

    /**
     * @return string
     */
    public function getBusinnessValueField()
    {
        return $this->businnessValueField;
    }

    /**
     * @param string $businnessValueField
     *
     * @return self
     */
    public function setBusinnessValueField($businnessValueField)
    {
        $this->businnessValueField = $businnessValueField;

        return $this;
    }
}
