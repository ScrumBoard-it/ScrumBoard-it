<?php

namespace ScrumBoardItBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/**
 * Registration type.
 *
 * @author Antony Pradel <antony.pradel@canaltp.fr>
 */
class RegistrationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'label' => "Nom d'utilisateur:",
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required' => true,
            ))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'options' => array('attr' => array('class' => 'form-control')),
                'required' => true,
                'first_options'  => array('label' => 'Mot de passe:'),
                'second_options' => array('label' => 'Confirmer le mot de passe:'),
            ))
            ->add('jira_url', TextType::class, array(
                'label' => "Inidiqer l'url de votre Jira:",
                'attr' => array(
                    'class' => 'form-control',
                ),
                'required' => false,
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ScrumBoardItBundle\Entity\Registration',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'registration';
    }
}
