<?php
namespace App\Form\Type;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;

class BinaryMaskChoiceType extends AbstractType implements DataTransformerInterface
{
    private $choices = [];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->choices = $options['choices'];
        $builder->addModelTransformer($this);
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefaults([
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function getParent(): ?string
    {
        return \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class;
    }

    public function transform($modelData): mixed
    {
        if ($modelData === null) {
            return [];
        } else if (!is_int($modelData)) {
            throw new \UnexpectedTypeException($modelData, 'integer');
        }

        foreach ($this->choices as $position => $power) {
            $formData[$position] = ($modelData & $power);
        }

        return $formData;
    }

    public function reverseTransform($formData): mixed
    {
        return array_sum($formData);
    }
}
