<?php
namespace ScrumBoardItBundle\Form\Type\Search;

<<<<<<< HEAD
use Symfony\Component\OptionsResolver\OptionsResolver;
=======
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
>>>>>>> brieuc/post-it-manager
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
=======
            'choices' => $jiraSearch->getProjects(),
            'label' => 'Projets',
            'empty_data' => null
        ))
            ->add('sprint', ChoiceType::class, array(
            'label' => 'Sprints',
            'choices' => $jiraSearch->getSprints()
>>>>>>> brieuc/post-it-manager
        ));
    }

    /**
     *
     * {@inheritdoc}
     *
     */
<<<<<<< HEAD
    public function configureOptions(OptionsResolver $resolver)
=======
    public function setDefaultOptions(OptionsResolverInterface $resolver)
>>>>>>> brieuc/post-it-manager
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

