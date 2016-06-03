<?php
namespace ScrumBoardItBundle\Form\Type\Search;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;

/**
 * Visitor search type
 *
 * @author Brieuc Pouliquen <brieuc.pouliquen@canaltp.fr>
 */
class VisitorSearchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $visitorSearch = $options['data'];
        $builder->add('project', ChoiceType::class, array(
            'label' => 'Projets',
            'choices' => $visitorSearch->getProjects(),
            'empty_data' => null,
            'placeholder' => 'Sélectionnez un projet'
        ))
        ->add('sprint', ChoiceType::class, array(
            'label' => 'Milestones actifs',
            'choices' => $visitorSearch->getSprints(),
            'placeholder' => 'Sélectionnez un filtre (optionnel)',
            'attr' => (empty($visitorSearch->getProject()) || empty($visitorSearch->getSprints())) ? array(
                'disabled' => 'disabled'
            ) : array()
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ScrumBoardItBundle\Entity\Search\SearchEntity'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'visitor_search';
    }
}
