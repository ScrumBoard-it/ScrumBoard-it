<?php

namespace CanalTP\ScrumBoardItBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Issues search form type
 * @author Vincent Catillon <contact@vincent-catillon.fr>
 */
class IssuesSearchType extends AbstractType
{
    /**
     * Issues service
     * @var ServiceInterface $service
     */
    private $service;

    /**
     * Constructor
     * @param $serviceManager
     */
    public function __construct($serviceManager)
    {
        $this->service = $serviceManager->getService();
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $boards = $this->service->getBoards();
        $boardId = $this->service->getBoardId();
        $sprints = $this->service->getSprints();
        $sprintId = $this->service->getSprintId();
        $builder
            ->add(
                'board',
                'choice',
                array(
                    'label' => 'Boards: ',
                    'choices' => $boards,
                    'data' => $boardId,
                    'required' => false
                )
            )
            ->add(
                'sprint',
                'choice',
                array(
                    'label' => 'Sprints non terminés: ',
                    'choices' => $sprints,
                    'data' => $sprintId,
                    'required' => false,
                    'attr' => empty($boardId) ? array('disabled' => 'disabled') : array()
                )
            );
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'csrf_protection' => false
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'issues_search';
    }
}
