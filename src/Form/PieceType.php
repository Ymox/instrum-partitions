<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PieceType extends AbstractType
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
            ->add('name', null, array(
                'label_format' => 'app.fields.piece.%name%',
            ))
            ->add('translation', null, array(
                'label_format' => 'app.fields.piece.%name%',
            ))
            ->add('movements', CollectionType::class, array(
                'entry_type'   => MovementType::class,
                'prototype'    => true,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => array(
                    'label' => false,
                ),
                'label_format' => 'app.fields.piece.%name%',
            ))
            ->add('parts', CollectionType::class, array(
                'entry_type'   => PartType::class,
                'prototype'    => true,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => array(
                    'label' => false,
                ),
                'label_format' => 'app.fields.piece.%name%',
            ))
            ->add('composers', null, array(
                'required'      => true,
                'label_format'  => 'app.fields.piece.%name%',
                'query_builder' => function (\App\Repository\PersonRepository $repo) {
                    return $repo->createQueryBuilder('p')->orderBy('p.lastName', 'asc');
                },
                'attr'          => array(
                    'class'    => 'addable',
                    'data-uri' => $this->urlGenerator->generate('person_new'),
                ),
            ))
            ->add('arrangers', null, array(
                'label_format'  => 'app.fields.piece.%name%',
                'query_builder' => function (\App\Repository\PersonRepository $repo) {
                    return $repo->createQueryBuilder('p')->orderBy('p.lastName', 'asc');
                },
                'attr'         => array(
                    'class'    => 'addable',
                    'data-uri' => $this->urlGenerator->generate('person_new'),
                ),
            ))
            ->add('instrumentation', null, array(
                'choice_label' => 'name',
                'label_format' => 'app.fields.piece.%name%',
                'choice_attr'  => function ($instrumentation) {
                    return [
                        'title' => $instrumentation->getNote(),
                    ];
                },
            ))
            ->add('type', null, array(
                'choice_label' => 'name',
                'label_format' => 'app.fields.piece.%name%',
            ))
            ->add('size', null, array(
                'required'      => true,
                'choice_label'  => 'name',
                'query_builder' => function (\App\Repository\SizeRepository $repo) {
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
                'label_format'  => 'app.fields.piece.%name%',
            ))
            ->add('location', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, array(
                'choices'      => \App\Entity\Piece::$LOCATIONS_LIST,
                'label_format' => 'app.fields.piece.%name%.label',
                'placeholder'  => 'app.fields.piece.location.placeholder',
                'choice_label' => function ($choice, $key, $value) {
                    return 'app.fields.piece.location.' . $value;
                },
                'attr'         => array(
                    'required' => 'required',
                ),
            ))
            ->add('states', \App\Form\Type\BinaryMaskChoiceType::class, array(
                'choices'      => \App\Entity\Piece::$STATES_LIST,
                'label_format' => 'app.fields.piece.%name%.label',
                'choice_label' => function ($choice, $key, $value) {
                    return 'app.fields.piece.states.' . $value;
                },
            ))
            ->add('missings', CollectionType::class, array(
                'entry_type'   => MissingType::class,
                'prototype'    => true,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label_format' => 'app.fields.piece.%name%',
                'entry_options' => array(
                    'label' => false,
                ),
            ))
            ->add('publisher', null, array(
                'label_format' => 'app.fields.piece.%name%',
            ))
            ->add('year', null, array(
                'label_format' => 'app.fields.piece.%name%',
                'attr'         => array(
                    'max' => date('Y'),
                ),
            ))
            ->add('reference', null, array(
                'label_format' => 'app.fields.piece.%name%',
            ))
            ->add('note', null, array(
                'label_format' => 'app.fields.piece.%name%',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => \App\Entity\Piece::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_piece';
    }
}
