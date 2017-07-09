<?php

namespace YSoft\InstrumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PieceType extends AbstractType
{
    private $urlGenerator;

    public function __construct(\Symfony\Component\Routing\Generator\UrlGeneratorInterface$urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label_format' => 'ysoft.instrum.fields.piece.%name%',
            ))
            ->add('translation', null, array(
                'label_format' => 'ysoft.instrum.fields.piece.%name%',
            ))
            ->add('composers', null, array(
                'required'      => true,
                'label_format'  => 'ysoft.instrum.fields.piece.%name%',
                'query_builder' => function (\YSoft\InstrumBundle\Repository\PersonRepository $repo) {
                    return $repo->createQueryBuilder('p')->orderBy('p.lastName', 'asc');
                },
                'attr'          => array(
                    'class'    => 'addable',
                    'data-uri' => $this->urlGenerator->generate('person_new'),
                ),
            ))
            ->add('arrangers', null, array(
                'label_format' => 'ysoft.instrum.fields.piece.%name%',
                'query_builder' => function (\YSoft\InstrumBundle\Repository\PersonRepository $repo) {
                    return $repo->createQueryBuilder('p')->orderBy('p.lastName', 'asc');
                },
                'attr'         => array(
                    'class' => 'addable',
                    'data-uri' => $this->urlGenerator->generate('person_new'),
                ),
            ))
            ->add('instrumentation', null, array(
                'choice_label' => 'name',
                'label_format' => 'ysoft.instrum.fields.piece.%name%',
                'choice_attr'  => function ($instrumentation) {
                    return [
                        'title' => $instrumentation->getNote(),
                    ];
                },
            ))
            ->add('type', null, array(
                'label_format' => 'ysoft.instrum.fields.piece.%name%',
            ))
            ->add('size', null, array(
                'required'      => true,
                'choice_label'  => 'name',
                'query_builder' => function (\YSoft\InstrumBundle\Repository\SizeRepository $repo) {
                    return $repo->createQueryBuilder('s')
                        ->orderBy('s.maxHeight', 'asc');
                },
                'choice_attr'   => function ($size) {
                    $title = '';
                    if ($size->getNote()) {
                        $title .= $size->getNote() . ' ';
                    }
                    $max = $size->getMaxDimension();
                    $min = $size->getMinDimension();
                    if ($max == $min) {
                        $title = $title ? ($title . '(' . $max . ')') : $max;
                    } else {
                        $title = $title ? ($title. '(' . $min . ' ⥤ ' . $max . ')') : $min . ' ⥤ ' . $max;
                    }
                    return [
                         'title' => $title,
                    ];
                },
                'label_format'  => 'ysoft.instrum.fields.piece.%name%',
            ))
            ->add('status', null, array(
                'choice_label' => 'name',
                'label_format' => 'ysoft.instrum.fields.piece.%name%',
            ))
            ->add('missings', CollectionType::class, array(
                'entry_type'   => MissingType::class,
                'prototype'    => true,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label_format' => 'ysoft.instrum.fields.piece.%name%',
            ))
            ->add('publisher', null, array(
                'label_format' => 'ysoft.instrum.fields.piece.%name%',
            ))
            ->add('year', null, array(
                'label_format' => 'ysoft.instrum.fields.piece.%name%',
                'attr'         => array(
                    'max' => date('Y'),
                ),
            ))
            ->add('reference', null, array(
                'label_format' => 'ysoft.instrum.fields.piece.%name%',
            ))
            ->add('note', null, array(
                'label_format' => 'ysoft.instrum.fields.piece.%name%',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'YSoft\InstrumBundle\Entity\Piece'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ysoft_instrumbundle_piece';
    }


}
