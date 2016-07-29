<?php

namespace ScrumBoardItBundle\Services\Persist;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityManager;
use ScrumBoardItBundle\Entity\Mapping\User;
use ScrumBoardItBundle\Exception\DatabaseException;

abstract class AbstractPersistClass
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(TokenStorage $token, EntityManager $entityManager)
    {
        $this->user = $token->getToken()->getUser();
        $this->entityManager = $entityManager;
    }

    /**
     * Persist an entity to the database.
     *
     * @param mixed $entity
     */
    public function flushEntity($entity)
    {
        try {
            $this->entityManager->flush($entity);
        } catch (\Exception $e) {
            throw new DatabaseException();
        }
    }

    /**
     * Entity getter.
     */
    abstract public function getEntity();

    /**
     * Create an empty instance for a user in the database.
     */
    abstract public function initiate($options = null);

    /**
     * Return Form and Entity classes.
     */
    abstract public function getFormConfiguration();
}
