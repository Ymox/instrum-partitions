<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Contracts\Translation\TranslatorInterface;

class PartType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('instrument', null, [
                'required' => true,
                'group_by' => function ($value, $key, $index) {
                    if ($value->isCommon()) {
                        $message = 'app.fields.instrument.common.true';
                    } else {
                        $message = 'app.fields.instrument.common.false';
                    }
                    return $this->translator->trans($message);
                },
                'label_format' => 'app.fields.part.%name%.label',
                'placeholder' => 'app.fields.part.instrument.placeholder',
                'attr' => [
                    'class' => 'searchable',
                ],
            ])
            ->add('clef', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'g',
                    'c',
                    'f',
                ],
                'choice_label' => function ($value, $key, $index) {
                    return 'app.fields.part.clef.choices.short.' . $value;
                },
                'label_format' => 'app.fields.part.%name%.label',
                'placeholder' => 'app.fields.part.clef.placeholder',
            ])
            ->add('number', IntegerType::class, [
                'required' => false,
                'label_format' => 'app.fields.part.%name%.label',
                'attr' => [
                    'placeholder' => 'app.fields.part.number.placeholder',
                    'min' => 0,
                    'max' => 4,
                ],
            ])
            ->add('solo', CheckboxType::class, [
                'required' => false,
                'label_format' => 'app.fields.part.%name%',
            ])
            ->add('upload', FileType::class, [
                'label_format' => 'app.fields.part.%name%',
                'file_property' => 'downloadPath',
                'required' => false,
            ])
            ->add('file', HiddenType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Part::class
        ]);
    }
}
