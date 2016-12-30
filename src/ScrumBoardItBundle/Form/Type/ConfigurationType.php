<?php

namespace ScrumBoardItBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Userstory', ChoiceType::class, array(
                'expanded' => true,
                'label' => 'Choix du template des User Story :',
                'choices' => array(
                    'default' => 0,
                    'custom1' => 1,
                    'custom2' => 2,
                    'custom3' => 3,
                ),
                'property_path' => 'configuration[Userstory]',
            ))
            ->add('Task', ChoiceType::class, array(
                'expanded' => true,
                'label' => 'Choix du template des Taches :',
                'choices' => array(
                    'default' => 0,
                ),
                'property_path' => 'configuration[Task]',
            ))
            ->add('Subtask', ChoiceType::class, array(
                'expanded' => true,
                'label' => 'Choix du template des Subtask :',
                'choices' => array(
                    'default' => 0,
                    'custom1' => 1,
                ),
                'property_path' => 'configuration[Subtask]',
            ))
            ->add('Poc', ChoiceType::class, array(
                'expanded' => true,
                'label' => 'Choix du template des Poc :',
                'choices' => array(
                    'default' => 0,
                    'custom1' => 1,
                ),
                'property_path' => 'configuration[Poc]',
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ScrumBoardItBundle\Entity\Mapping\User',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'template';
    }
}
