<?php

namespace ScrumBoardItBundle\Entity;

use Symfony\Component\HttpFoundation\Request;

class Configuration
{
    /**
     * User Story.
     *
     * @var number
     */
    private $userStory;

    /**
     * Sub Task.
     *
     * @var number
     */
    private $subTask;

    /**
     * Poc.
     *
     * @var number
     */
    private $poc;

    public function __construct(Request $request)
    {
        $session = $request->getSession();
        if (null !== $session->get('template')) {
            $this->userStory = $session->get('template')['user_story'];
            $this->subTask = $session->get('template')['sub_task'];
            $this->poc = $session->get('template')['poc'];
        } else {
            $this->userStory = 0;
            $this->subTask = 0;
            $this->poc = 0;
        }
    }

    /**
     * User Story getter.
     *
     * @return number
     */
    public function getUserStory()
    {
        return $this->userStory;
    }

    /**
     * Sub Task getter.
     *
     * @return number
     */
    public function getSubTask()
    {
        return $this->subTask;
    }

    /**
     * Poc getter.
     *
     * @return number
     */
    public function getPoc()
    {
        return $this->poc;
    }

    /**
     * User Story setter.
     *
     * @param number $userStory
     *
     * @return self
     */
    public function setUserStory($userStory)
    {
        $this->userStory = $userStory;

        return $this;
    }

    /**
     * Sub Task setter.
     *
     * @param number $subTask
     *
     * @return self
     */
    public function setSubTask($subTask)
    {
        $this->subTask = $subTask;

        return $this;
    }

    /**
     * Poc setter.
     *
     * @param number $poc
     *
     * @return self
     */
    public function setPoc($poc)
    {
        $this->poc = $poc;

        return $this;
    }
}
