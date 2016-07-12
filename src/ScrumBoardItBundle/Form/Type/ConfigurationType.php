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
            ->add('user_story', ChoiceType::class, array(
                'expanded' => true,
                'label' => 'Choix du template des User Story: ',
                'choices' => array(
                    'default' => 0,
                    'custom1' => 1,
                ),
                'property_path' => 'configuration[user_story]',
            ))
            ->add('sub_task', ChoiceType::class, array(
                'expanded' => true,
                'label' => 'Choix du template des Subtask: ',
                'choices' => array(
                    'default' => 0,
                    'custom1' => 1,
                ),
                'property_path' => 'configuration[sub_task]',
            ))
            ->add('poc', ChoiceType::class, array(
                'expanded' => true,
                'label' => 'Choix du template des Poc: ',
                'choices' => array(
                    'default' => 0,
                    'custom1' => 1,
                ),
                'property_path' => 'configuration[poc]',
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ScrumBoardItBundle\Entity\User',
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
