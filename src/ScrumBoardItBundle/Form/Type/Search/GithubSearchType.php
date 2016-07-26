<?php

namespace ScrumBoardItBundle\Form\Type\Search;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;

/**
 * Github search type.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class GithubSearchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $githubSearch = $options['data'];
        $builder->add('project', ChoiceType::class, array(
            'label' => 'Dépôts',
            'choices' => $githubSearch->getProjects(),
            'placeholder' => 'Choisissez un dépôt',
            'preferred_choices' => $options['favorites'],
        ))
            ->add('sprint', ChoiceType::class, array(
            'label' => 'Milestones actifs',
            'choices' => $githubSearch->getSprints(),
            'placeholder' => 'Sélectionnez un milestone (optionnel)',
            'attr' => (empty($githubSearch->getProject()) || empty($githubSearch->getSprints())) ? array(
                'disabled' => 'disabled',
            ) : array(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ScrumBoardItBundle\Entity\Search\SearchEntity',
            'favorites' => array(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'github_search';
    }
}
