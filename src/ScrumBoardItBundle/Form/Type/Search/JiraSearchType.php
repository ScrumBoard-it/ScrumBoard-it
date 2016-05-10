<?php
namespace ScrumBoardItBundle\Form\Type\Search;

<<<<<<< HEAD
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
=======
use Symfony\Component\OptionsResolver\OptionsResolver;
>>>>>>> d80ea57... Debug after merging
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
<<<<<<< HEAD
            'choices' => $jiraSearch->getProjects(),
            'label' => 'Projets',
            'empty_data' => null
        ))
            ->add('sprint', ChoiceType::class, array(
            'label' => 'Sprints',
            'choices' => $jiraSearch->getSprints()
=======
            'label' => 'Projets',
            'choices' => $jiraSearch->getProjects(),
            'empty_data' => null,
            'placeholder' => 'Choisissez un projet en cours',
            'attr' => array(
                'id' => $jiraSearch->getProject()
            )
        ))
            ->add('sprint', ChoiceType::class, array(
            'label' => 'Sprints non terminés',
            'choices' => $jiraSearch->getSprints(),
            'attr' => (empty($jiraSearch->getProject()) || empty($jiraSearch->getSprint())) ? array(
                'disabled' => 'disabled'
            ) : array()
>>>>>>> d80ea57... Debug after merging
        ));
    }

    /**
     *
     * {@inheritdoc}
     *
     */
<<<<<<< HEAD
    public function setDefaultOptions(OptionsResolverInterface $resolver)
=======
    public function configureOptions(OptionsResolver $resolver)
>>>>>>> d80ea57... Debug after merging
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

