<?php

namespace ScrumBoardItBundle\Entity;

class Template
{
    /**
     * User Story
     * @var number
     */
    private $userStory;

    /**
     * Sub Task
     * @var number
     */
    private $subTask;

    /**
     * Poc
     * @var number
     */
    private $poc;

    /**
     * User Story getter
     *
     * @return number
     */
    function getUserStory() {
        return $this->userStory;
    }

    /**
     * Sub Task getter
     *
     * @return number
     */
    function getSubTask() {
        return $this->subTask;
    }

    /**
     * Poc getter
     *
     * @return number
     */
    function getPoc() {
        return $this->poc;
    }

    /**
     * User Story setter
     * @param number $userStory
     * @return self
     */
    function setUserStory($userStory) {
        $this->userStory = $userStory;

        return $this;
    }

    /**
     * Sub Task setter
     * @param number $subTask
     * @return self
     */
    function setSubTask($subTask) {
        $this->subTask = $subTask;

        return $this;
    }

    /**
     * Poc setter
     * @param number $poc
     * @return self
     */
    function setPoc($poc) {
        $this->poc = $poc;

        return $this;
    }


}
