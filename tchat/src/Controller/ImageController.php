<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use App\Service\Entity\ImageService;
use Google\Cloud\Storage\StorageClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{

    /**
     * @Route("/json/images/create",  name="image.create")
     * @param Request $request
     * @param ImageService $imageService
     * @return JsonResponse
     * @throws \Exception
     */
    public function ajaxCreateImage(Request $request, ImageService $imageService)
    {
        $imageName = $_FILES['uploadFile']['name'];
        $gsName = $_FILES["uploadFile"]["tmp_name"];

        $signedUrl = $imageService->gcsUpload($imageName, $gsName);

        return new JsonResponse($signedUrl);
    }
}
