<?php

namespace ScrumBoardItBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('jira_url', TextType::class, array(
            'label' => 'Nouvelle adresse Jira:',
            'attr' => array(
                'placeholder' => 'http://jira.domaine.com/',
                'class' => 'form-control',
            ),
            'property_path' => 'jiraUrl',
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ScrumBoardItBundle\Entity\User',
        ));
    }

    public function getName()
    {
        return 'edit_profile';
    }
}
