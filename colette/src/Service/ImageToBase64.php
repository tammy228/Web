<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
class ImageToBase64
{
    public function imgTo64(string $img_file)
    {
        $path = $img_file;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);

        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}