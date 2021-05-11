<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use App\Service\Base64ToFile;
use App\Service\Entity\NewsEntityService;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class NewsController extends AbstractController
{
    /***
     *
     *      __  __ _____ ___   _____
     *     / / / // ___// _ \ / ___/
     *    / /_/ /(__  )/  __// /
     *    \__,_//____/ \___//_/
     *
     */

    /**
     * @Route("/news", name="news.list")
     * @param Request $request
     * @param NewsRepository $newsRepository
     * @param ContainerInterface $container
     * @return Response
     */
    public function listNews(Request $request, NewsRepository $newsRepository, ContainerInterface $container)
    {
        $locale = $request->getLocale();
        $news = $newsRepository->findBy([],['createAt'=>'DESC']);
        $paginator = $container->get("knp_paginator");
        $multipleNews = $paginator->paginate(
            $news, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );

        return $this->render('website/news/list.html.twig',[
            'multipleNews' => $multipleNews,
            'locale' => $locale,
        ]);
    }

    /**
     * @Route("/news/latest", name="news.latest.list")
     * @param Request $request
     * @param NewsRepository $newsRepository
     * @param ContainerInterface $container
     * @return Response
     */
    public function listLatestNews(Request $request, NewsRepository $newsRepository, ContainerInterface $container)
    {
        $locale = $request->getLocale();
        $news = $newsRepository->findBy([],['updateAt'=>'DESC']);
        $paginator = $container->get("knp_paginator");
        $multipleNews = $paginator->paginate(
            $news, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );

        return $this->render('website/news/list.html.twig',[
            'multipleNews' => $multipleNews,
            'locale' => $locale,
        ]);
    }

    /**
     * @Route("/news/{id}", name="news.fetch")
     * @param Request $request
     * @param $id
     * @param NewsEntityService $newsEntityService
     * @return Response
     */
    public function fetchNews(Request $request, $id, NewsEntityService $newsEntityService)
    {
        $locale = $request->getLocale();
        return $this->render("website/news/fetch.html.twig", array(
            "news" => $newsEntityService->fetchById($id),
            "locale" => $locale,
        ));

    }

    /***
     *                  __            _
     *      ____ _ ____/ /____ ___   (_)____
     *     / __ `// __  // __ `__ \ / // __ \
     *    / /_/ // /_/ // / / / / // // / / /
     *    \__,_/ \__,_//_/ /_/ /_//_//_/ /_/
     *
     */

    /**
     * @Route("admin/news", name="admin.news.list")
     */
    public function adminListNews()
    {
        return $this->render("admin/news/list.html.twig", array(
            "listType" => "all",
        ));
    }

    /**
     * @Route("admin/news/create", name="admin.news.create")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function adminCreateNews(Request $request, FileUploader $fileUploader)
    {
        $news = New News();
        $form = $this->createForm(NewsType::class, $news);
        $form -> handleRequest($request);

        if($form->isSubmitted() && $form-> isValid())
        {

            //處理縮圖，只有一張，所以index 直接給0
            $thumbnail_image = $_POST['thumbnail_image'];
            $thumbnail_image_name = $_POST['thumbnail_image_name'];
            $thumbnail_image = explode(",data:",$thumbnail_image);
            $thumbnail_image_name = explode(",", $thumbnail_image_name);
            $content = "";

            if($thumbnail_image[0]){
                $image = new Base64ToFile($thumbnail_image[0], $thumbnail_image_name[0]);
                $contentName = $fileUploader->upload($image);
                $content = "/uploads/images/" . $contentName;
            }
            $news->setThumbNail($content);

            //處理內容圖
            $content_image = $_POST['content_image'];
            $content_image_name = $_POST['content_image_name'];
            $content_image = explode(",data:",$content_image);
            $content_image_name = explode(",", $content_image_name);
            $content=[];

            /**
             * @var UploadedFile $image
             */
            for ($i=0; $i<count($content_image); $i++)
            {
                if($content_image[$i]) {
                    $image = new Base64ToFile($content_image[$i], $content_image_name[$i]);
                    $contentName = $fileUploader->upload($image);
                    $content[$i] = "/uploads/images/" . $contentName;
                }
            }
            $news->setImages($content);


            $em = $this->getDoctrine()->getManager();
            $em->persist($news);
            $em->flush();

            return $this->redirectToRoute('admin.news.list');
        }
        return $this->render('admin/news/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/news/{uuid}/info", name="admin.news.fetch", methods={"GET"})
     * @param Request $request
     * @param $uuid
     * @return string
     */
    public function adminFetchNews(Request $request, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $newsRepository = $em->getRepository(News::class);
        $news = $newsRepository->findOneBy(array("uuid" =>$uuid));

        if(!$news) return $this->redirectToRoute("admin.news.list");

        return $this->render("admin/news/fetch.html.twig", array(
            "news" => $news
        ));
    }

    /**
     * @Route("/admin/news/{uuid}/update", name="admin.news.update")
     * @param Request $request
     * @param $uuid
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function adminUpdateNews(Request $request, $uuid, FileUploader $fileUploader)
    {
        $em = $this->getDoctrine()->getManager();
        $newsRepository = $em->getRepository(News::class);
        $news = $newsRepository->findOneBy( array("uuid" => $uuid));

        if(!$news) return $this->redirectToRoute("admin.news.list");

        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //處理縮圖，只有一張，所以index 直接給0
            $thumbnail_image = $_POST['thumbnail_image'];
            $thumbnail_image_name = $_POST['thumbnail_image_name'];
            $thumbnail_image = explode(",data:",$thumbnail_image);
            $thumbnail_image_name = explode(",", $thumbnail_image_name);
            $content = "";

            if($thumbnail_image[0]){
                $image = new Base64ToFile($thumbnail_image[0], $thumbnail_image_name[0]);
                $contentName = $fileUploader->upload($image);
                $content = "/uploads/images/" . $contentName;
                //有更新縮圖才set，否則保持原本縮圖
                $news->setThumbNail($content);
            }

            $content_image = $_POST['content_image'];
            $content_image_name = $_POST['content_image_name'];
            $content_image = explode(",data:",$content_image);
            $content_image_name = explode(",", $content_image_name);
            $content=[];

            /**
             * @var UploadedFile $image
             */
            for ($i=0; $i<count($content_image); $i++)
            {
                if($content_image[$i]) {
                    $image = new Base64ToFile($content_image[$i], $content_image_name[$i]);
                    $contentName = $fileUploader->upload($image);
                    $content[$i] = "/uploads/images/" . $contentName;
                }
            }

            if($content_image[0]){
                $image = new Base64ToFile($content_image[0], $content_image_name[0]);
                $contentName = $fileUploader->upload($image);
                $content = "/uploads/images/" . $contentName;
                //有更新內容圖才set，否則保持原本內容圖
                $news->setImages($content);
            }

            $em->persist($news);
            $em->flush();

            return $this->redirectToRoute("admin.news.list");
        }

        return $this->render("admin/news/update.html.twig", array(
            "form" => $form->createView(),
            "news" => $news
        ));
    }

    /**
     * @Route("admin/news/{uuid}/delete", name="admin.news.delete")
     * @param $uuid
     * @return RedirectResponse
     */
    public function adminDeleteNews($uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $newsRepository = $em->getRepository(News::class);
        $news = $newsRepository->findOneBy(array("uuid" => $uuid));

        if (!$news) return $this->redirectToRoute("admin.news.list");

        $images = $news->getImages();
        if($images)
            unlink($this->getParameter('kernel.project_dir') . '/public' . $images[0]);

        $em->remove($news);

        $em->flush();

        return $this->redirectToRoute('admin.news.list');
    }



    /**
     * @Route("admin/image/keep", name="admin.keep.image")
     * @return Response
     */
    public function adminUpdateImage()
    {
        $folder_name = '/uploads/';
        if (!empty($_FILES)) {
            $_SESSION['file'] = $_FILES['file'];
            return new Response('200', 200);
        }

        return new Response('200', 200);
    }

    /**
     * @Route("/admin/news/{uuid}/images/delete", name="admin.news-images.delete")
     * @param Request $request
     * @param $uuid
     * @return Response
     */
    public function adminDeleteNewsAllImages(Request $request, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $newsRepository = $em->getRepository(News::class);
        $news = $newsRepository->findOneBy(array("uuid" => $uuid));

        $imagePaths = $news->getImages();
        foreach ($imagePaths as $imagePath)
            unlink($this->getParameter('kernel.project_dir').'/public'.$imagePath);

        $news->setImages([]);
        $em->persist($news);
        $em->flush();

        return $this->redirectToRoute("admin.news.update", array(
            "uuid" => $uuid
        ));

    }



}
