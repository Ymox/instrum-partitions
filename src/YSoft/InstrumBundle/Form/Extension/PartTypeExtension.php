<?php

namespace YSoft\InstrumBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class PartTypeExtension extends AbstractTypeExtension
{
    /**
     * @var \Symfony\Component\Translation\Translator
     */
    private $translator;

    public function __construct(\Symfony\Component\Translation\Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return \Symfony\Component\Form\Extension\Core\Type\FileType::class;
    }

    /**
     * Return the class of the type being extended.
     */
    public static function getExtendedTypes()
    {
        // return FormType::class to modify (nearly) every field in the system
        return [
            \Symfony\Component\Form\Extension\Core\Type\FileType::class,
        ];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired([
                'file_property',
            ])
            ->setDefined('download_name')
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($options['file_property'])) {
            /** @var \YSoft\InstrumBundle\Entity\Part $part */
            $part = $form->getParent()->getData();

            $fileUrl = null;
            $downloadName = null;
            if ($part && $part->getFile()) {
                $accessor = PropertyAccess::createPropertyAccessor();
                $fileUrl = $accessor->getValue($part, $options['file_property']);
                if (!empty($options['download_name'])) {
                    $downloadName = $options['download_name'];
                } else {
                    $downloadName = $part->getDownloadName();
                }
            }

            // sets a "file_url" variable that will be available when rendering this field
            $view->vars['file_url'] = $fileUrl;
            $view->vars['download_name'] = $downloadName;
            $view->vars['part_id'] = $part ? $part->getId() : '__id__';
        }
    }
}