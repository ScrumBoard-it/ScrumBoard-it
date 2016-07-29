<?php

namespace ScrumBoardItBundle\Form\Type\Profile;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Favorites Profile type.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class FavoritesProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $favorites = $options['data'];
        if (!empty($favorites->getJira())) {
            $builder->add('jira', ChoiceType::class, array(
                'label' => 'Jira',
                'choices' => $favorites->getJira(),
                'expanded' => true,
                'multiple' => true,
            ));
        }
        if (!empty($favorites->getGithub())) {
            $builder->add('github', ChoiceType::class, array(
                'label' => 'GitHub',
                'choices' => $favorites->getGithub(),
                'expanded' => true,
                'multiple' => true,
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ScrumBoardItBundle\Entity\Mapping\Favorites',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'favorites_profile';
    }
}
