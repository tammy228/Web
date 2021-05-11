<?php

namespace App\Controller;

use App\Entity\Banner;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/admin/index", name="admin.index")
     */
    public function adminIndex()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/", name="user.index")
     */
    public function userIndex(Request $request)
    {
        $locale = $request->getLocale();
        $em = $this->getDoctrine()->getManager();
        $bannerRepository = $em->getRepository(Banner::class);
        $banners = $bannerRepository->findAll();


        return $this->render('website/index.html.twig',[
            'banners' => $banners,
            'locale' => $locale
        ]);
    }
}
