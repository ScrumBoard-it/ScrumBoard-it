<?php
namespace ScrumBoardItBundle\Form\Type\Search;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;

class GithubSearchType extends AbstractType
{
    /**
     *
     * {@inheritdoc}
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $githubSearch = $options['data'];
        $builder->add('project', ChoiceType::class, array(
            'label' => 'Dépôts',
            'choices' => $githubSearch->getProjects(),
            'empty_data' => null,
            'placeholder' => 'Choisissez un projet en cours',
            'attr' => array(
                'id' => $githubSearch->getProject()
            )
        ))
        ->add('sprint', ChoiceType::class, array(
            'label' => 'Sprints non terminés',
            'choices' => $githubSearch->getSprints(),
            'placeholder' => 'Sélectionnez un sprint en cours',
            'attr' => (empty($githubSearch->getProject()) || empty($githubSearch->getSprints())) ? array(
                'disabled' => 'disabled'
            ) : array()
        ));
    }
    
    /**
     *
     * {@inheritdoc}
     *
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ScrumBoardItBundle\Entity\Search\SearchEntity'
        ));
    }
    
    /**
     *
     * {@inheritdoc}
     *
     */
    public function getName()
    {
        return 'github_search';
    }
}