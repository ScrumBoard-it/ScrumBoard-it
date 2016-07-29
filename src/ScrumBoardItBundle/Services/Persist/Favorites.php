<?php

namespace ScrumBoardItBundle\Services\Persist;

use ScrumBoardItBundle\Exception\DatabaseException;
use ScrumBoardItBundle\Entity\Mapping\Favorites as Fav;
use ScrumBoardItBundle\Form\Type\Profile\FavoritesProfileType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityManager;

class Favorites extends AbstractPersistClass
{
    /**
     * @var Session
     */
    private $session;

    public function __construct(TokenStorage $token, EntityManager $entityManager, Session $session)
    {
        parent::__construct($token, $entityManager);
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        if ($this->user->getUsername() === 'visitor') {
            return $this->session->get('favorites', []);
        }
        try {
            return $this->entityManager->getRepository('ScrumBoardItBundle:Mapping\Favorites')
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
    public function initiate($options = null)
    {
        $favorites = new Fav();
        $favorites->setUserId($this->user->getId());
        $this->entityManager->persist($favorites);
        $this->flushEntity($favorites);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormConfiguration()
    {
        return array(
            'form' => FavoritesProfileType::class,
            'entity' => Fav::class,
        );
    }
}
