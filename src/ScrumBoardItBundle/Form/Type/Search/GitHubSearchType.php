<?php
namespace ScrumBoardItBundle\Form\Type\Search;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;

class GitHubSearchType extends AbstractType
{
    /**
     *
     * {@inheritdoc}
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $gitHubSearch = $options['data'];
        $builder->add('project', ChoiceType::class, array(
            'label' => 'Dépôts',
            'choices' => $gitHubSearch->getProjects(),
            'empty_data' => null,
            'placeholder' => 'Choisissez un projet en cours',
            'attr' => array(
                'id' => $gitHubSearch->getProject()
            )
        ))
        ->add('sprint', ChoiceType::class, array(
            'label' => 'Sprints non terminés',
            'choices' => $gitHubSearch->getSprints(),
            'placeholder' => 'Sélectionnez un sprint en cours',
            'attr' => (empty($gitHubSearch->getProject()) || empty($gitHubSearch->getSprints())) ? array(
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