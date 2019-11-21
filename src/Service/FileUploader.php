<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function upload(UploadedFile $file, $path)
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        try {
            $file->move($path, $fileName);
        } catch (\Exception $e) {
            return null;
        }

        return $fileName;
    }
}
