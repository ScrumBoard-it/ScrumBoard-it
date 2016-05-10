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
            'label' => 'Projets',
            'choices' => $jiraSearch->getProjects(),
            'empty_data' => null,
            'placeholder' => 'Choisissez un projet en cours'
        ))
            ->add('sprint', ChoiceType::class, array(
            'label' => 'Sprints non terminés',
            'choices' => $jiraSearch->getSprints(),
            'attr' => empty($jiraSearch->getProject()) ? array('disabled' => 'disabled') : array()
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

