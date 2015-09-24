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
        $builder
            ->add(
                'board',
                'choice',
                array(
                    'label' => 'Boards: ',
                    'choices' => array('' => '') + $this->service->getBoards()
                )
            )
            ->add(
                'sprint',
                'choice',
                array(
                    'label' => 'Sprints actifs: ',
                    'choices' => array('' => '') + $this->service->getSprints(),
                    'required' => false
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
