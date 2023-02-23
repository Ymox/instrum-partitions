<?php

namespace App\Form;

use App\Config\Location;
use App\Config\State;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LendingType extends AbstractType
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('band', null, [
                'choice_label' => 'name',
                'label_format' => 'app.fields.lending.%name%',
                'attr'         => [
                    'class'    => 'addable searchable',
                    'data-uri' => $this->urlGenerator->generate('band_new'),
                ],
            ])
            ->add('contact', null, [
                'label_format' => 'app.fields.lending.%name%',
            ])
            ->add('ours', null, [
                'label_format' => 'app.fields.lending.%name%',
            ])
            ->add('start', null, [
                'widget'  => 'single_text',
                'label_format' => 'app.fields.lending.%name%',
            ])
            ->add('end', null, [
                'widget'  => 'single_text',
                'label_format' => 'app.fields.lending.%name%',
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                $form = $event->getForm();
                $lending = $event->getData();


                $form->add('pieces', null, [
                    'choice_label'  => function($piece) use ($lending) {
                        $pieceAsString = $piece->getId() . ' - ' . $piece->getName() . ($piece->getTranslation() ? ' (' . $piece->getTranslation() . ')' : null);
                        return $pieceAsString;
                    },
                    'query_builder' => function (\App\Repository\PieceRepository $repo) use ($lending) {
                        $qb = $repo->createQueryBuilder('p');
                        $qb ->leftJoin('p.lendings', 'l')
                            ->where($qb->expr()->orX(
                                $qb->expr()->andX(
                                    $qb->expr()->in('p.location', ':locations'),
                                    $qb->expr()->eq(new \Doctrine\ORM\Query\Expr\Func('BIT_AND', ['p.states', ':state']), ':state')
                                ),
                                $qb->expr()->eq('l.id', ':lendingId')
                            ))
                            ->setParameter(':locations', [Location::SHELF, Location::STOWED])
                            ->setParameter(':lendingId', $lending->getId())
                            ->setParameter(':state', State::VERIFIED)
                        ;

                        return $qb;
                    },
                    'label_format'  => 'app.fields.lending.%name%',
                    'attr' => [
                        'class' => 'searchable',
                    ]
                ]);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Lending::class
        ]);
    }
}
