<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
class Base64ToFile extends UploadedFile
{
    public function __construct(string $dataUrl, string $originalName)
    {
        if(preg_match("/data/", $dataUrl))
        {
            $dataUrlArray = explode('data:', $dataUrl);
            $dataUrl = $dataUrlArray[1];
        }
        $data = explode( ';base64,', $dataUrl);

        $filePath = tempnam(sys_get_temp_dir(), 'UploadedFile');
        $imgData = base64_decode($data[1]);
        file_put_contents($filePath, $imgData);
        $error = null;
        $mimeType = $data[0];
        $test = true;

        parent::__construct($filePath, $originalName, $mimeType, $error, $test);
    }
}