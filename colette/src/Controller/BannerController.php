<?php

namespace App\Controller;

use App\Entity\Banner;
use App\Form\BannerType;
use App\Service\Base64ToFile;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BannerController extends AbstractController
{
    /***
     *                  __            _
     *      ____ _ ____/ /____ ___   (_)____
     *     / __ `// __  // __ `__ \ / // __ \
     *    / /_/ // /_/ // / / / / // // / / /
     *    \__,_/ \__,_//_/ /_/ /_//_//_/ /_/
     *
     */

    /**
     * @Route("admin/banner/create", name="admin.banner.create")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function adminCreateBanner(Request $request, FileUploader $fileUploader)
    {
        $banner = New Banner();
        $form = $this->createForm(BannerType::class, $banner);
        $form -> handleRequest($request);

        if($form->isSubmitted() && $form-> isValid())
        {
            $banner->setUuid();
            /**
             * @var UploadedFile $image
             */
            $image = $form['images']->getData();
            //images database 形式是用array 存
            $imagePaths = [];
            $hashedPath = "/uploads/images/".$fileUploader->upload($image);
            $imagePaths[] = $hashedPath;
            $banner->setImages($imagePaths);

            $em = $this->getDoctrine()->getManager();
            $em->persist($banner);
            $em->flush();

            return $this->redirectToRoute('admin.banner.list');
        }
        return $this->render('admin/banner/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/banner", name="admin.banner.list")
     * @param Request $request
     * @return Response
     */
    public function adminListBanner(Request $request)
    {
        $em = $this ->getDoctrine()->getManager();
        $bannerRepository = $em->getRepository(Banner::class);

        $banner = $bannerRepository->findOneBy(array('name'=>'admin'));

        return $this -> render("admin/banner/list.html.twig", array(
            "banner" => $banner,
        ));
    }

    /**
     * @Route("/admin/banner/{loc}/update", name="admin.banner.update")
     * @param Request $request
     * @param $loc
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function adminUpdateBanner(Request $request, $loc, FileUploader $fileUploader)
    {
        $em = $this->getDoctrine()->getManager();
        $bannerRepository = $em->getRepository(Banner::class);
        $banner = $bannerRepository->findOneBy( array("name" => 'admin'));

        if(!$banner) return $this->redirectToRoute("admin.banner.list");

        $form = $this->createForm(BannerType::class, $banner);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $imagePaths = $banner->getImages();
            $dataUrls = $_POST['image'];
            $imageNames = $_POST['image_name'];

            $dataUrls = explode(",data:",$dataUrls);
            $imageNames = explode(",", $imageNames);
            $content="";

            /**
             * @var UploadedFile $image
             */
            for ($i=0; $i<count($dataUrls); $i++)
            {
                $image = new Base64ToFile($dataUrls[$i], $imageNames[$i]);
                $contentName = $fileUploader->upload($image);
                $content="/uploads/images/".$contentName;
                $imagePaths[$loc][] = $content;

            }
//            /**
//             * @var UploadedFile $images
//             */
//            $images = $form['images']->getData();
//            $imagePaths = $banner->getImages();
//            foreach($images as $image)
//            {
//                $hashedPath = "/uploads/images/".$fileUploader->upload($image);
//                $imagePaths[$loc][] = $hashedPath;
//            }

            $banner->setImages($imagePaths);
            $em->persist($banner);
            $em->flush();

            return $this->redirectToRoute("admin.banner.list");
        }

        return $this->render("admin/banner/update.html.twig", array(
            "form" => $form->createView(),
            "banner" => $banner,
            'loc'=>$loc,
        ));
    }

    /**
     * @Route("/admin/banner/{uuid}/info", name="admin.news.banner", methods={"GET"})
     * @param Request $request
     * @param $uuid
     * @return string
     */
    public function adminFetchBanner(Request $request, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $bannerRepository = $em->getRepository(Banner::class);
        $banner = $bannerRepository->findOneBy(array("uuid" =>$uuid));

        if(!$banner) return $this->redirectToRoute("admin.banner.list");

        return $this->render("admin/banner/fetch.html.twig", array(
            "banner" => $banner
        ));
    }

    /**
     * @Route("/admin/banner/{uuid}/images/delete", name="admin.banner-images.delete")
     * @param Request $request
     * @param $uuid
     * @return Response
     */
    public function adminDeleteBannerImages(Request $request, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $bannerRepository = $em->getRepository(Banner::class);
        $banner = $bannerRepository->findOneBy( array("name" => 'admin'));

        $imagePaths = $banner->getImages();
//        foreach ($imagePaths[$uuid] as $imagePath)
//            unlink($this->getParameter('kernel.project_dir').'/public'.$imagePath);

        $imagePaths[$uuid]= [];
        $banner->setImages($imagePaths);
        $em->persist($banner);
        $em->flush();

        return $this->redirectToRoute("admin.banner.update", array(
            "loc" => $uuid
        ));

    }

}
