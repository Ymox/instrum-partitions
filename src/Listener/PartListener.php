<?php

namespace App\Listener;

use App\Entity\Part;
use App\Service\FileUploader;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

#[AsEntityListener]
class PartListener
{
    private FileUploader $uploader;

    private ?string $uploadPath = null;

    private ?string $downloadPath = null;

    public function __construct(
        FileUploader $uploader,
        $uploadPath,
        $downloadPath
    ) {
        $this->uploader = $uploader;
        $this->uploadPath = $uploadPath;
        $this->downloadPath = $downloadPath;
    }

    public function prePersist(Part $entity)
    {
        $this->uploadFile($entity);
    }

    public function postLoad(Part $entity)
    {
        $entity->setDownloadFolder($this->downloadPath);
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

        // only upload new files
        if (!($file = $entity->getUpload())) {
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