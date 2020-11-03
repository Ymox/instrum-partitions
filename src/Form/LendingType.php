<?php

namespace App\Form;

use App\Entity\Piece;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;

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
                'label_format' => 'app.fields.lending.%name%',
                'attr'         => array(
                    'class'    => 'addable',
                    'data-uri' => $this->urlGenerator->generate('band_new'),
                ),
            ))
            ->add('contact', null, array(
                'label_format' => 'app.fields.lending.%name%',
            ))
            ->add('ours', null, array(
                'label_format' => 'app.fields.lending.%name%',
            ))
            ->add('start', null, array(
                'widget'  => 'single_text',
                'label_format' => 'app.fields.lending.%name%',
            ))
            ->add('end', null, array(
                'widget'  => 'single_text',
                'label_format' => 'app.fields.lending.%name%',
            ))
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                $form = $event->getForm();
                $lending = $event->getData();


                $form->add('pieces', null, array(
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
                            ->setParameter(':locations', array(Piece::LOCATION_SHELF, Piece::LOCATION_STOWED))
                            ->setParameter(':lendingId', $lending->getId())
                            ->setParameter(':state', Piece::STATE_VERIFIED)
                        ;

                        return $qb;
                    },
                    'label_format'  => 'app.fields.lending.%name%',
                ));
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => \App\Entity\Lending::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_lending';
    }


}
