<?php

namespace ScrumBoardItBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Login type.
 *
 * @author Antony Pradel <antony.pradel@canaltp.fr>
 */
class BugtrackerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => "Nom d'utilisateur",
                    'class' => 'form-control',
                ),

            ))
            ->add('password', PasswordType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Mot de passe',
                    'class' => 'form-control',
                ),
            ))
            ->add('api', ChoiceType::class, array(
                'label' => 'Se connecter au bugtracker:',
                'choices' => array(
                    'GitHub' => 'github',
                    'Jira' => 'jira',
                ),
                'attr' => array(
                    'class' => 'form-control',
                ),
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ScrumBoardItBundle\Entity\Login',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bugtracker';
    }
}
