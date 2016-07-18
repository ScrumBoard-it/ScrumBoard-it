<?php

namespace ScrumBoardItBundle\Form\Type\Profile;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

/**
 * Jira Profile type.
 */
class JiraProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add()
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ScrumBoardItBundle\Entity\Profile\JiraProfileEntity',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'jira';
    }
}
