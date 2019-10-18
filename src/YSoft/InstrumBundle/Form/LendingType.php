<?php

namespace YSoft\InstrumBundle\Form;

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
                'label_format' => 'ysoft.instrum.fields.lending.%name%',
                'attr'         => array(
                    'class'    => 'addable',
                    'data-uri' => $this->urlGenerator->generate('band_new'),
                ),
            ))
            ->add('contact', null, array(
                'label_format' => 'ysoft.instrum.fields.lending.%name%',
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
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                $form = $event->getForm();
                $lending = $event->getData();


                $form->add('pieces', null, array(
                    'choice_label'  => function($piece) use ($lending) {
                        $pieceAsString = $piece->getId() . ' - ' . $piece->getName() . ($piece->getTranslation() ? ' (' . $piece->getTranslation() . ')' : null);
                        return $pieceAsString;
                    },
                    'query_builder' => function (\YSoft\InstrumBundle\Repository\PieceRepository $repo) use ($lending) {
                        $qb = $repo->createQueryBuilder('p');
                        $qb ->innerJoin('p.status', 's')
                            ->leftJoin('p.lendings', 'l')
                            ->where($qb->expr()->orX(
                                $qb->expr()->notIn('s.id', ':statuses'),
                                $qb->expr()->eq('l.id', ':lendingId')
                            ))
                            ->setParameter(':statuses', array('lent', 'returned'))
                            ->setParameter(':lendingId', $lending->getId())
                        ;

                        return $qb;
                    },
                    'label_format'  => 'ysoft.instrum.fields.lending.%name%',
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
