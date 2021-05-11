<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsType;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
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
            $news->setUuid();

            /**
             * @var UploadedFile $images
             */
            $images = $form['images']->getData();
            //images database 形式是用array 存
            $imagePaths = [];
            foreach($images as $image)
            {
                $hashedPath = "/uploads/images/".$fileUploader->upload($image);
                array_push($imagePaths, $hashedPath);
            }
            $news->setImages($imagePaths);


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
     * @Route("/admin/news", name="admin.news.list")
     * @param Request $request
     * @return Response
     */
    public function adminListNews(Request $request)
    {
        $em = $this ->getDoctrine()->getManager();
        $newsRepository = $em->getRepository(News::class);

        $multipleNews = $newsRepository->findBy(array());

        return $this -> render("admin/news/list.html.twig", array(
            "multipleNews" => $multipleNews,
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
            /**
             * @var UploadedFile $images
             */
            $images = $form['images']->getData();
            $imagePaths = $news->getImages();
            foreach($images as $image)
            {
                $hashedPath = "/uploads/images/".$fileUploader->upload($image);
                array_push($imagePaths, $hashedPath);
            }
            $news->setImages($imagePaths);

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
     * @Route("/admin/news/{uuid}/delete", name="admin.news.delete")
     * @param Request $request
     * @param $uuid
     * @return Response
     */
    public function adminDeleteNews(Request $request, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $newsRepository = $em->getRepository(News::class);

        /**
         * @var News $news
         */
        $news = $newsRepository->findOneBy(array("uuid" => $uuid));

        if(!$news) return $this->redirectToRoute("admin.news.list");

        $em->remove($news);
        $em->flush();

        return $this->redirectToRoute("admin.news.list");
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
