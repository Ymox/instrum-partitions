<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovementType extends AbstractType
{
    private $urlGenerator;

    public function __construct(\Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label_format' => 'app.fields.piece.%name%',
            ])
            ->add('translation', null, [
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
                'attr'         => [
                    'class'    => 'addable searchable',
                    'data-uri' => $this->urlGenerator->generate('person_new'),
                ],
            ])
            ->add('type', null, [
                'choice_label' => 'name',
                'label_format' => 'app.fields.piece.%name%',
            ])
            ->add('missings', CollectionType::class, [
                'entry_type'   => MissingType::class,
                'prototype'    => true,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label_format' => 'app.fields.piece.%name%',
            ])
            ->add('note', null, [
                'label_format' => 'app.fields.piece.%name%',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Piece::class
        ]);
    }
}
