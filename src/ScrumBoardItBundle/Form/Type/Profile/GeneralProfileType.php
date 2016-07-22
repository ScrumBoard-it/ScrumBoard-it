<?php

namespace ScrumBoardItBundle\Form\Type\Profile;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/**
 * General Profile type.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class GeneralProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('old_password', PasswordType::class, array(
                'label' => 'Entrez votre mot de passe actuel:',
                'attr' => array(
                    'placeholder' => 'Mot de passe actuel',
                    'class' => 'form-control',
                ),
                'required' => true,
                'property_path' => 'oldPassword',
            ))
            ->add('new_password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'required' => true,
                'first_options' => array(
                    'label' => 'Entrez un nouveau mot de passe:',
                    'attr' => array(
                    'placeholder' => 'Nouveau mot de passe',
                    'class' => 'form-control',
                ), ),
                'second_options' => array(
                    'label' => 'Confirmez le nouveau mot de passe:',
                    'attr' => array(
                    'placeholder' => 'Nouveau mot de passe',
                    'class' => 'form-control',
                ), ),
                'property_path' => 'newPassword',
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ScrumBoardItBundle\Entity\Profile\GeneralProfileEntity',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'general_profile';
    }
}
