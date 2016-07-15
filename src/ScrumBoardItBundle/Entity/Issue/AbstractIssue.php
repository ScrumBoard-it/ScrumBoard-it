<?php

namespace ScrumBoardItBundle\Entity\Issue;

/**
 * Description of AbstractIssue.
 *
 * @author Johan Rouve <johan.rouve@gmail.com>
 */
class AbstractIssue implements IssueInterface, \JsonSerializable
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $link;

    /**
     * @var bool
     */
    private $printed;

    /**
     * @var bool
     */
    private $userStory;

    /**
     * @var bool
     */
    private $proofOfConcept;

    /**
     * @var string
     */
    private $project;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $number;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $complexity;

    /**
     * @var int
     */
    private $businessValue;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $timeBox;

    /**
     * @var int
     */
    private $returnOnInvestment;

    /**
     * Type getter.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Type setter.
     *
     * @param string $type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Link getter.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Link setter.
     *
     * @param string $link
     *
     * @return self
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Printed getter.
     *
     * @return bool
     */
    public function isPrinted()
    {
        return $this->printed;
    }

    /**
     * Printed setter.
     *
     * @param bool $printed
     *
     * @return self
     */
    public function setPrinted($printed)
    {
        $this->printed = $printed;

        return $this;
    }

    /**
     * User Story getter.
     *
     * @return bool
     */
    public function isUserStory()
    {
        return $this->userStory;
    }

    /**
     * User Story setter.
     *
     * @param bool $value
     *
     * @return self
     */
    public function setUserStory($value)
    {
        $this->userStory = $value;

        return $this;
    }

    /**
     * Proof of Concept getter.
     *
     * @return bool
     */
    public function isProofOfConcept()
    {
        return $this->proofOfConcept;
    }

    /**
     * Proof of Concept setter.
     *
     * @param bool $proofOfConcept
     *
     * @return self
     */
    public function setProofOfConcept($proofOfConcept)
    {
        $this->proofOfConcept = $proofOfConcept;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * {@inheritdoc}
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Number getter.
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Number setter.
     *
     * @param int $number
     *
     * @return self
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getShortTitle()
    {
        $shortTitle = $this->title;
        $lenght = 15;

        if (empty($this->description) or strlen($this->description) < 20) {
            $lenght += 30;
        }

        if (strlen($shortTitle) > $lenght) {
            $shortTitle = substr($shortTitle, 0, $lenght);
            $shortTitle .= '...';
        }

        return $shortTitle;
    }

    /**
     * {@inheritdoc}
     */
    public function getComplexity()
    {
        return $this->complexity;
    }

    /**
     * {@inheritdoc}
     */
    public function setComplexity($complexity)
    {
        $this->complexity = $complexity;

        return $this;
    }

    /**
     * Business Value getter.
     *
     * @return int
     */
    public function getBusinessValue()
    {
        return $this->businessValue;
    }

    /**
     * Business Value Setter.
     *
     * @param int $value
     *
     * @return self
     */
    public function setBusinessValue($value)
    {
        $this->businessValue = $value;

        return $this;
    }

    /**
     * Description getter.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function getShortDescription()
    {
        $shortDescription = preg_replace('/http\:\/\/.*\s/isU', '', $this->description);
        $lenght = 75;

        if ($this->userStory) {
            if (strlen($shortDescription) > $lenght * 1.5) {
                $shortDescription = substr($shortDescription, 0, $lenght * 1.5);
                $shortDescription .= '...';
            }

            return $shortDescription;
        }

        if (strlen($shortDescription) > $lenght) {
            $shortDescription = substr($shortDescription, 0, $lenght);
            $shortDescription .= '...';
        }

        return $shortDescription;
    }

    /**
     * Description setter.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Time Box getter.
     *
     * @return string
     */
    public function getTimeBox()
    {
        return $this->timeBox;
    }

    /**
     * Time Box setter.
     *
     * @param string $timeBox
     *
     * @return self
     */
    public function setTimeBox($timeBox)
    {
        $this->timeBox = $timeBox;

        return $this;
    }

    /**
     * Return On Investment getter.
     *
     * @return int
     */
    public function getReturnOnInvestment()
    {
        return $this->returnOnInvestment;
    }

    /**
     * Return On Investment setter.
     *
     * @return self
     */
    public function setReturnOnInvestment()
    {
        if (is_numeric($this->complexity) && is_numeric($this->businessValue) && $this->complexity > 0 && $this->businessValue > $this->complexity) {
            $this->returnOnInvestment = round($this->businessValue / $this->complexity, 0);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array(
            'type' => $this->getType(),
            'link' => $this->getLink(),
            'printed' => $this->isPrinted(),
            'user_story' => $this->isUserStory(),
            'proof_of_concept' => $this->isProofOfConcept(),
            'project' => $this->getProject(),
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'short_title' => $this->getShortTitle(),
            'complexity' => $this->getComplexity(),
            'business_Value' => $this->getBusinessValue(),
            'description' => $this->getDescription(),
            'short_description' => $this->getShortDescription(),
            'time_box' => $this->getTimeBox(),
        );
    }
}
