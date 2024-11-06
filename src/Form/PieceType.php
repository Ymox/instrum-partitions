<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PieceType extends AbstractType
{
    private $urlGenerator;

    public function __construct(\Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label_format' => 'app.fields.piece.%name%',
            ])
            ->add('translation', null, [
                'label_format' => 'app.fields.piece.%name%',
            ])
            ->add('movements', CollectionType::class, [
                'entry_type'   => MovementType::class,
                'prototype'    => true,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => [
                    'label' => false,
                ],
                'label_format' => 'app.fields.piece.%name%',
            ])
            ->add('parts', CollectionType::class, [
                'entry_type'   => PartType::class,
                'prototype'    => true,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => [
                    'label' => false,
                ],
                'label_format' => 'app.fields.piece.%name%',
            ])
            ->add('composers', null, [
                'required'      => true,
                'label_format'  => 'app.fields.piece.%name%',
                'query_builder' => function (\App\Repository\PersonRepository $repo) {
                    return $repo->createQueryBuilder('p')->orderBy('p.lastName', 'asc');
                },
                'attr'          => [
                    'class'    => 'addable searchable',
                    'data-uri' => $this->urlGenerator->generate('person_new'),
                ],
            ])
            ->add('arrangers', null, [
                'label_format'  => 'app.fields.piece.%name%',
                'query_builder' => function (\App\Repository\PersonRepository $repo) {
                    return $repo->createQueryBuilder('p')->orderBy('p.lastName', 'asc');
                },
                'attr'          => [
                    'class'    => 'addable searchable',
                    'data-uri' => $this->urlGenerator->generate('person_new'),
                ],
            ])
            ->add('instrumentation', null, [
                'choice_label' => 'name',
                'label_format' => 'app.fields.piece.%name%',
                'choice_attr'  => function ($instrumentation) {
                    return [
                        'title' => $instrumentation->getNote(),
                    ];
                },
            ])
            ->add('type', null, [
                'choice_label' => 'name',
                'label_format' => 'app.fields.piece.%name%',
            ])
            ->add('size', null, [
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
                        $title = $title ? ($title . '(' . $min . ' ⥤ ' . $max . ')') : $min . ' ⥤ ' . $max;
                    }
                    return [
                        'title' => $title,
                    ];
                },
                'label_format'  => 'app.fields.piece.%name%',
                'choice_translation_domain' => false,
            ])
            ->add('location', EnumType::class, [
                'class'        => \App\Config\Location::class,
                'required'     => false,
                'choices'      => \App\Entity\Piece::$LOCATIONS_LIST,
                'label_format' => 'app.fields.piece.%name%.label',
                'placeholder'  => 'app.fields.piece.location.placeholder',
                'choice_label' => function ($choice, $key, $value) {
                    return 'app.fields.piece.location.' . $value;
                }
            ])
            ->add('states', \App\Form\Type\BinaryMaskEnumType::class, [
                'class'        => \App\Config\State::class,
                'choices'      => \App\Entity\Piece::$STATES_LIST,
                'label_format' => 'app.fields.piece.%name%.label',
                'choice_label' => function ($choice, $key, $value) {
                    return 'app.fields.piece.states.' . $value;
                },
            ])
            ->add('missings', CollectionType::class, [
                'entry_type'   => MissingType::class,
                'prototype'    => true,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label_format' => 'app.fields.piece.%name%',
                'entry_options' => [
                    'label' => false,
                ],
            ])
            ->add('publisher', null, [
                'label_format' => 'app.fields.piece.%name%',
                'attr' => [
                    'class' => 'searchable',
                ],
            ])
            ->add('year', null, [
                'label_format' => 'app.fields.piece.%name%',
                'attr'         => [
                    'max' => date('Y'),
                ],
            ])
            ->add('reference', null, [
                'label_format' => 'app.fields.piece.%name%',
            ])
            ->add('note', null, [
                'label_format' => 'app.fields.piece.%name%',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Piece::class
        ]);
    }
}
