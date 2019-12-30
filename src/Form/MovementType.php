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
            ->add('type', null, array(
                'choice_label' => 'name',
                'label_format' => 'app.fields.piece.%name%',
            ))
            ->add('status', null, array(
                'choice_label' => 'name',
                'label_format' => 'app.fields.piece.%name%',
                'attr'         => array(
                    'required' => 'required',
                ),
            ))
            ->add('missings', CollectionType::class, array(
                'entry_type'   => MissingType::class,
                'prototype'    => true,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
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
            'data_class' => 'App\Entity\Piece'
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
