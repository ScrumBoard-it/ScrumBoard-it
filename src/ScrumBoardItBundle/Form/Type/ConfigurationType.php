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
                'label' => false,
                'choices' => array(
                    'default' => 0,
                    'custom1' => 1,
                    'custom2' => 2
                ),
            ))
            ->add('sub_task', ChoiceType::class, array(
                'expanded' => true,
                'label' => false,
                'choices' => array(
                    'default' => 0,
                    'custom1' => 1,
                    'custom2' => 2
                ),
            ))
            ->add('poc', ChoiceType::class, array(
                'expanded' => true,
                'label' => false,
                'choices' => array(
                    'default' => 0
                ),
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ScrumBoardItBundle\Entity\Configuration'
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