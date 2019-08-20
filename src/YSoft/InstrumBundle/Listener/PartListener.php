<?php

namespace YSoft\InstrumBundle\Listener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use YSoft\InstrumBundle\Entity\Part;
use YSoft\InstrumBundle\Service\FileUploader;

class PartListener
{
    /**
     * @var \YSoft\InstrumBundle\Service\FileUploader
     */
    private $uploader;

    /**
     * @var string
     */
    private $uploadPath;

    /**
     * @var string
     */
    private $downloadPath;

    public function __construct(FileUploader $uploader, $uploadPath, $downloadPath)
    {
        $this->uploader = $uploader;
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
            $entity->setUpload(new File(ltrim($this->uploadPath, '/') . '/' . $fileName));
            $entity->setDownloadFolder($this->downloadPath);
        }
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
}