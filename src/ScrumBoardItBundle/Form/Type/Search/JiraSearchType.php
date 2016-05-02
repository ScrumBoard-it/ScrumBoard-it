<?php
namespace ScrumBoardItBundle\Form\Type\Search;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
/**
 * Jira search type
 */
class JiraSearchType extends AbstractType
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $jiraSearch = $options['data'];
        $builder->add('project', ChoiceType::class, array(
            'choices' => $jiraSearch->getProjects(),
            'label' => 'Projets',
            'empty_data' => null,
            'placeholder' => 'Choisissez un projet en cours'
        ))
            ->add('sprint', ChoiceType::class, array(
            'label' => 'Sprints',
            'choices' => $jiraSearch->getSprints()
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
            'data_class' => 'ScrumBoardItBundle\Entity\Search\JiraSearch'
        ));
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getName()
    {
        return 'jira_search';
    }
}

