<?php

namespace ScrumBoardItBundle\Form\Type\Search;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Jira search type.
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class JiraSearchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $jiraSearch = $options['data'];
        $builder->add('project', ChoiceType::class, array(
            'label' => 'Tableaux',
            'choices' => $jiraSearch->getProjects(),
            'placeholder' => 'Choisissez un tableau',
            'attr' => array(
                'id' => $jiraSearch->getProject(),
            ),
            'preferred_choices' => array_keys($options['favorites']),
        ))
            ->add('sprint', ChoiceType::class, array(
            'label' => 'Sprints non terminés',
            'choices' => $jiraSearch->getSprints(),
            'attr' => (empty($jiraSearch->getProject()) || empty($jiraSearch->getSprint())) ? array(
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
        return 'jira_search';
    }
}
