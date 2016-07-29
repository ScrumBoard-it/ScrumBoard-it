<?php

namespace ScrumBoardItBundle\Services\Persist;

use ScrumBoardItBundle\Exception\DatabaseException;
use ScrumBoardItBundle\Entity\Mapping\JiraConfiguration as JiraConf;
use ScrumBoardItBundle\Form\Type\Profile\JiraProfileType;

class JiraConfiguration extends AbstractPersistClass
{
    /**
     * @var string
     */
    const DEFAULT_TAG = 'Post-it';

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        try {
            return $this->entityManager->getRepository('ScrumBoardItBundle:Mapping\JiraConfiguration')
            ->findOneBy(array(
                'userId' => $this->user->getId(),
            ));
        } catch (\Exception $e) {
            throw new DatabaseException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function flushEntity($entity)
    {
        if ($entity instanceof JiraConf && empty($entity->getPrintedTag())) {
            $entity->setPrintedTag(self::DEFAULT_TAG);
        }
        parent::flushEntity($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function initiate($options = null)
    {
        $jiraConfiguration = new JiraConf();
        $jiraConfiguration->setUserId($this->user->getId());
        $this->entityManager->persist($jiraConfiguration);
        $this->flushEntity($jiraConfiguration);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormConfiguration()
    {
        return array(
            'form' => JiraProfileType::class,
            'entity' => JiraConf::class,
        );
    }
}
