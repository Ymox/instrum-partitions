<?php

namespace App\Listener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Part;

class PartListener
{
    /**
     * @var \App\Service\FileUploader
     */
    private $uploader;

    /**
     * @var \Symfony\Contracts\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $uploadPath;

    /**
     * @var string
     */
    private $downloadPath;

    public function __construct(\App\Service\FileUploader $uploader, \Symfony\Contracts\Translation\TranslatorInterface $translator, $uploadPath, $downloadPath)
    {
        $this->uploader = $uploader;
        $this->translator = $translator;
        $this->uploadPath = $uploadPath;
        $this->downloadPath = $downloadPath;
    }

    public function prePersist(Part $entity)
    {
        $this->uploadFile($entity);
    }

    public function postLoad(Part $entity)
    {
        if ($fileName = $entity->getFile()) {
            $entity->setUpload(new File(rtrim($this->uploadPath, '/') . '/' . $fileName));
            $entity->setDownloadFolder($this->downloadPath);
        }

        $this->setTranslatedStrings($entity);
    }

    public function preUpdate(Part $entity)
    {
        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        // upload only works for Part entities
        if (!$entity instanceof Part) {
            return;
        }

        $file = $entity->getUpload();

        // only upload new files
        if (!$file) {
            return;
        } elseif ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file, $this->uploadPath);
            $entity->setFile($fileName);
        } elseif ($file instanceof File) {
            // prevents the full file path being saved on updates
            // as the path is set on the postLoad listener
            $entity->setFile($file->getFilename());
        }
    }

    private function setTranslatedStrings(Part $entity)
    {
        // Download and display names
        $translationString = 'app.part';
        if ($entity->isSolo()) {
            $translationString .= '.solo';
        }
        if ($entity->getNumber()) {
            $translationString .= '.numbered';
        }
        if ($entity->getClef()) {
            $translationString .= '.with_clef';
        } else {
            $translationString .= '.without_clef';
        }

        $parameters = [
            'instrument' => (string) $entity->getInstrument(),
            'number'     => $entity->getNumber(),
        ];

        $display = $this->translator->trans($translationString, array_merge(
            $parameters,
            [
                'clef' => $entity->getClef() ? $this->translator->trans('app.fields.part.clef.choices.short.' . $entity->getClef()) : '',
            ]
        ));
        $downloadName = preg_replace('`♭`' , 'b', $this->translator->trans($translationString, array_merge(
            $parameters,
            [
                'clef' => $entity->getClef() ? $this->translator->trans('app.fields.part.clef.choices.long.' . $entity->getClef()) : '',
            ]
        )));

        $entity
            ->setDisplay($display)
            ->setDownloadName($downloadName)
        ;
    }
}