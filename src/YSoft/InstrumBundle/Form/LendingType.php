<?php

namespace YSoft\InstrumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LendingType extends AbstractType
{
    private $urlGenerator;

    public function __construct(\Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('band', null, array(
                'choice_label' => 'name',
                'label_format' => 'ysoft.instrum.fields.lending.%name%',
                'attr'         => array(
                    'class'    => 'addable',
                    'data-uri' => $this->urlGenerator->generate('band_new'),
                ),
            ))
            ->add('contact', null, array(
                'label_format' => 'ysoft.instrum.fields.lending.%name%',
            ))
            ->add('pieces', null, array(
                'query_builder' => function (\YSoft\InstrumBundle\Repository\PieceRepository $repo) {
                    $qb = $repo->createQueryBuilder('p');
                    return $qb->innerJoin('p.status', 's')->where($qb->expr()->notIn('s.id', ['lent', 'returned']));
                },
                'choice_label'  => function($piece) {
                    $pieceAsString = $piece->getName() . ($piece->getTranslation() ? ' (' . $piece->getTranslation() . ')' : null);
                    return $pieceAsString;
                },
                'label_format'  => 'ysoft.instrum.fields.lending.%name%',
            ))
            ->add('ours', null, array(
                'label_format' => 'ysoft.instrum.fields.lending.%name%',
            ))
            ->add('start', null, array(
                'widget'  => 'single_text',
                'label_format' => 'ysoft.instrum.fields.lending.%name%',
            ))
            ->add('end', null, array(
                'widget'  => 'single_text',
                'label_format' => 'ysoft.instrum.fields.lending.%name%',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'YSoft\InstrumBundle\Entity\Lending'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ysoft_instrumbundle_lending';
    }


}
