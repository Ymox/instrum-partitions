<?php

namespace App\Form\Extension;

use App\Entity\Translatable\PartTranslatableMessage;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PartTypeExtension extends AbstractTypeExtension
{
    private TranslatorInterface $translator;

    private UrlGeneratorInterface $urlGenerator;

    private string $downloadPath;

    public function __construct(TranslatorInterface $translator, UrlGeneratorInterface $urlGenerator, string $downloadPath)
    {
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
        $this->downloadPath = $downloadPath;
    }

    public function getExtendedType()
    {
        return \Symfony\Component\Form\Extension\Core\Type\FileType::class;
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            \Symfony\Component\Form\Extension\Core\Type\FileType::class,
        ];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired([
                'file_property',
            ])
            ->setDefined('download_name')
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $part = $form->getParent()->getData();

        $fileUrl = null;
        $downloadName = null;
        if ($part && $part->getFile()) {
            $accessor = PropertyAccess::createPropertyAccessor();
            if ($part->getId()) {
                $fileUrl = $this->urlGenerator->generate('part_download', ['ids' => [$part->getId()]]);
            } else {
                $fileUrl = $this->urlGenerator->generate('file_download', ['file' => ltrim($this->downloadPath, '/') . $accessor->getValue($part, $options['file_property'])]);
            }
            if (!empty($options['download_name'])) {
                $downloadName = $options['download_name'];
            } else if ($part->getId()) {
                $downloadName = preg_replace(['` +`', '`\.`', '`♭`'] , ['_', '-', 'b'], (new PartTranslatableMessage($part))->trans($this->translator));
            } else {
                $downloadName = mb_substr($fileUrl, 16);
            }
        }

        $view->vars['file_url'] = $fileUrl;
        $view->vars['download_name'] = $downloadName;
        $view->vars['part_id'] = $part ? $part->getId() : '__id__';
    }
}