<?php

namespace ScrumBoardItBundle\Form\Type\Profile;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
            ->add('url', TextType::class, array(
                'label' => 'URL de votre application Jira',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Nouvelle URL Jira',
                    'class' => 'form-control',
                ),
            ))
            ->add('printed_tag', TextType::class, array(
                'label' => 'Etiquette des tickets imprimés',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Nouveau tag',
                    'class' => 'form-control',
                ),
                'property_path' => 'printedTag',
            ))
            ->add('complexity_field', TextType::class, array(
                'label' => "Nom du champ de la complexité technique d'un ticket (non obligatoire)",
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Nouveau nom de champ de complexité',
                    'class' => 'form-control',
                ),
                'property_path' => 'complexityField',
            ))
            ->add('businnessvalue_field', TextType::class, array(
                'label' => "Nom du champ de la valeur commerciale d'un ticket (non obligatoire)",
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Nouveau nom de champ de valeur commerciale',
                    'class' => 'form-control',
                ),
                'property_path' => 'businnessValueField',
            ));
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
        return 'jira_profile';
    }
}
