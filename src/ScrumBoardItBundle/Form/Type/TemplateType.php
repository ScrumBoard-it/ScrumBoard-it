<?php

namespace ScrumBoardItBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TemplateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userStory', ChoiceType::class, array(
                'label' => 'Choix du template pour les user story:',
                'expanded' => true,
                'choices' => array(
                    1 => 1,
                    2 => 2,
                    3 => 3
                ),
            ))
            ->add('subTask', ChoiceType::class, array(
                'label' => 'Choix du template pour les sub task:',
                'expanded' => true,
                'choices' => array(
                    1 => 1,
                    2 => 2,
                    3 => 3
                ),
            ))
            ->add('poc', ChoiceType::class, array(
                'label' => 'Choix du template pour les POC:',
                'expanded' => true,
                'choices' => array(
                    1 => 1
                ),
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ScrumBoardItBundle\Entity\Template'
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