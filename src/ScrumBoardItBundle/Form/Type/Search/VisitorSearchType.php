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
        
        $builder->add('project', ChoiceType::class, array(
            'empty_data' => null,
            'placeholder' => 'Choisissez un dépôt'
        ))
            ->add('sprint', ChoiceType::class, array(
            'empty_data' => null,
            'placeholder' => 'Sélectionnez un milestone (optionnel)',
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
