<?php

namespace YSoft\InstrumBundle\Listener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use YSoft\InstrumBundle\Entity\Part;

class PartListener
{
    /**
     * @var \YSoft\InstrumBundle\Service\FileUploader
     */
    private $uploader;

    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
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

    public function __construct(\YSoft\InstrumBundle\Service\FileUploader $uploader, \Symfony\Component\Translation\TranslatorInterface $translator, $uploadPath, $downloadPath)
    {
        $this->uploader = $uploader;
        $this->translator = $translator;
        $this->uploadPath = $uploadPath;
        $this->downloadPath = $downloadPath;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Part) {
            return;
        }

        if ($fileName = $entity->getFile()) {
            $entity->setUpload(new File(rtrim($this->uploadPath, '/') . '/' . $fileName));
            $entity->setDownloadFolder($this->downloadPath);
        }

        $this->setTranslatedStrings($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

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

    private function setTranslatedStrings($entity)
    {
        if (!$entity instanceof Part) {
            return;
        }

        // Download and display names
        $translationString = 'ysoft.instrum.part';
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

        $parameters = array(
            '%instrument%' => (string) $entity->getInstrument(),
            '%number%'     => $entity->getNumber(),
        );

        $display = $this->translator->trans($translationString, array_merge(
            $parameters,
            array(
                '%clef%' => $entity->getClef() ? $this->translator->trans('ysoft.instrum.fields.part.clef.choices.short.' . $entity->getClef()) : '',
            )
        ));
        $downloadName = preg_replace('`♭`' , 'b', $this->translator->trans($translationString, array_merge(
            $parameters,
            array(
                '%clef%' => $entity->getClef() ? $this->translator->trans('ysoft.instrum.fields.part.clef.choices.long.' . $entity->getClef()) : '',
            )
        )));

        $entity
            ->setDisplay($display)
            ->setDownloadName($downloadName)
        ;
    }
}