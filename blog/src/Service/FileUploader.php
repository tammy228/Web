<?php

namespace App\Service;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        //$originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = hash('sha512', uniqid()).".".$file->guessClientExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }


    public function getOriginalName(UploadedFile $file)
    {
        return $file->getClientOriginalName();
    }
    

}

