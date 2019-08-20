<?php

namespace YSoft\InstrumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PartType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translator = $this->translator;
        $builder
            ->add('instrument', null, array(
                'required' => true,
                'group_by' => function ($value, $key, $index) {
                    if ($value->isCommon()) {
                        return 'ysoft.instrum.fields.instrument.common.true';
                    } else {
                        return 'ysoft.instrum.fields.instrument.common.false';
                    }
                },
                'choice_label' => function ($value, $key, $index) use ($translator) {
                    if ($value->getKey()) {
                        return $translator->trans('ysoft.instrum.fields.part.instrument.choices', array(
                            '%instrument%' => $value,
                            '%key%' => $translator->trans('ysoft.instrum.fields.instrument.key.choices.' . $value->getKey()))
                        );
                    } else {
                        return $value;
                    }
                },
                'label_format' => 'ysoft.instrum.fields.part.%name%.label',
            ))
            ->add('clef', ChoiceType::class, array(
                'required' => false,
                'choices' => array(
                    'g',
                    'c',
                    'f',
                ),
                'choice_label' => function ($value, $key, $index) {
                    return 'ysoft.instrum.fields.part.clef.choices.short.' . $value;
                },
                'label_format' => 'ysoft.instrum.fields.part.%name%.label',
            ))
            ->add('number', IntegerType::class, array(
                'required' => false,
                'label_format' => 'ysoft.instrum.fields.part.%name%',
            ))
            ->add('solo', CheckboxType::class, array(
                'required' => false,
                'label_format' => 'ysoft.instrum.fields.part.%name%',
            ))
            ->add('upload', FileType::class, array(
                'label_format' => 'ysoft.instrum.fields.part.%name%',
                'file_property' => 'downloadPath',
                'required' => false,
            ))
            ->add('file', HiddenType::class, array(
                'required' => false,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => \YSoft\InstrumBundle\Entity\Part::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ysoft_instrumbundle_part';
    }
}
