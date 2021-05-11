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
            $dataUrls = $_POST['image'];
            $imageNames = $_POST['image_name'];

            $dataUrls = explode(",data:",$dataUrls);
            $imageNames = explode(",", $imageNames);
            $content=[];

            /**
             * @var UploadedFile $image
             */
            for ($i=0; $i<count($dataUrls); $i++)
            {
                if($dataUrls[$i]) {
                    $image = new Base64ToFile($dataUrls[$i], $imageNames[$i]);
                    $contentName = $fileUploader->upload($image);
                    $content[$i] = "/uploads/images/" . $contentName;
                }else{
                    $content[$i] = "/img/noPhoto.png";
                }
            }

            $banner->setImages($content);

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

        $banners = $bannerRepository->findBy(array());

        return $this -> render("admin/banner/list.html.twig", array(
            "banners" => $banners,
        ));
    }

    /**
     * @Route("/admin/banner/{uuid}/update", name="admin.banner.update")
     * @param Request $request
     * @param $uuid
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function adminUpdateBanner(Request $request, $uuid, FileUploader $fileUploader)
    {
        $em = $this->getDoctrine()->getManager();
        $bannerRepository = $em->getRepository(Banner::class);
        $banner = $bannerRepository->findOneBy( array("uuid" => $uuid));

        if(!$banner) return $this->redirectToRoute("admin.banner.list");

        $form = $this->createForm(BannerType::class, $banner);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $dataUrls = $_POST['image'];
            $imageNames = $_POST['image_name'];

            $dataUrls = explode(",data:",$dataUrls);
            $imageNames = explode(",", $imageNames);
            $content=[];

            /**
             * @var UploadedFile $image
             */
            for ($i=0; $i<count($dataUrls); $i++)
            {
                if($dataUrls[$i]) {
                    $image = new Base64ToFile($dataUrls[$i], $imageNames[$i]);
                    $contentName = $fileUploader->upload($image);
                    $content[$i] = "/uploads/images/" . $contentName;
                }
            }

            if($dataUrls[0]){
                $image = new Base64ToFile($dataUrls[0], $imageNames[0]);
                $contentName = $fileUploader->upload($image);
                $content[0] = "/uploads/images/" . $contentName;
                //有更新縮圖才set，否則保持原本縮圖
                $banner->setImages($content);
            }

            $em->persist($banner);
            $em->flush();

            return $this->redirectToRoute("admin.banner.list");
        }

        return $this->render("admin/banner/update.html.twig", array(
            "form" => $form->createView(),
            "banner" => $banner
        ));
    }

    /**
     * @Route("/admin/banner/{uuid}/info", name="admin.banner.fetch", methods={"GET"})
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
     * @Route("admin/banner/{uuid}/delete", name="admin.banner.delete")
     * @param $uuid
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function adminDeleteBanner($uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $bannerRepository = $em->getRepository(Banner::class);
        $banner = $bannerRepository->findOneBy(array("uuid" => $uuid));

        if (!$banner) return $this->redirectToRoute("admin.banner.list");

        $images = $banner->getImages();
        if($images) {
            foreach($images as $image)
                unlink($this->getParameter('kernel.project_dir') . '/public' . $image);
        }

        $em->remove($banner);

        $em->flush();

        return $this->redirectToRoute('admin.banner.list');
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
