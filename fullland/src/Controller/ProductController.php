<?php

namespace App\Controller;

use App\Entity\ProductionRange;
use App\Form\ProductionRangeType;
use App\Service\Base64ToFile;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /***
     *
     *      __  __________  _____
     *     / / / / ___/ _ \/ ___/
     *    / /_/ (__  )  __/ /
     *    \__,_/____/\___/_/
     *
     */

    /**
     * @Route("/product" ,name="product")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function product(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository(ProductionRange::class);

        $products = $productRepository->findBy(array("showCase" => 0),array("sort"=>"ASC"));

        $locale = $request->getLocale();

        return $this->render("website/product.html.twig", array(
            "products" => $products,
            'locale' => $locale
        ));
    }

    /***
     *                 __          _                               __           __
     *      ____ _____/ /___ ___  (_)___     ____  _________  ____/ /_  _______/ /_
     *     / __ `/ __  / __ `__ \/ / __ \   / __ \/ ___/ __ \/ __  / / / / ___/ __/
     *    / /_/ / /_/ / / / / / / / / / /  / /_/ / /  / /_/ / /_/ / /_/ / /__/ /_
     *    \__,_/\__,_/_/ /_/ /_/_/_/ /_/  / .___/_/   \____/\__,_/\__,_/\___/\__/
     *                                   /_/
     */


    /**
     * @Route("admin/product/{uuid}/update", name="admin.product.update", methods={"GET","POST"})
     * @param Request $request
     * @param ProductionRange $product
     * @param FileUploader $fileUploader
     * @param $uuid
     * @return Response
     * @throws Exception
     */
    public function adminUpdateProduct(Request $request, ProductionRange $product, FileUploader $fileUploader, $uuid): Response
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository(ProductionRange::class);
        $product = $productRepository->findOneBy(array("uuid" => $uuid));

        if(!$product) return $this->redirectToRoute("admin.product.list");

        $form = $this->createForm(ProductionRangeType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
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
                if($dataUrls[$i]){
                    $image = new Base64ToFile($dataUrls[$i], $imageNames[$i]);
                    $contentName = $fileUploader->upload($image);
                    $content[$i]="/uploads/images/".$contentName;
                }
            }
            //要先確定原本有沒有圖片，
            //因為content 是array 如果直接setImages的話原本的圖片會不見
            $imageUrls = $product->getImages();
            if($imageUrls)
                $content = array_merge($imageUrls,$content);

            $product->setImages($content);

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('admin.product.list');
        }

        return $this->render('admin/product/update.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/product/{uuid}/info", name="admin.product.fetch", methods={"GET"})
     * @param Request $request
     * @param $uuid
     * @return string
     */
    public function adminFetchProduct(Request $request, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository(ProductionRange::class);
        $product = $productRepository->findOneBy(array("uuid" =>$uuid));

        if(!$product) return $this->redirectToRoute("admin.product.list");

        return $this->render("admin/product/fetch.html.twig", array(
            "product" => $product
        ));
    }

    /**
     * @Route("admin/product", name="admin.product.list")
     * @param Request $request
     * @return Response
     */
    public function adminListProduct(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository(ProductionRange::class);

        $products = $productRepository->findBy(array("showCase" =>1),array("sort"=>"ASC"));

        return $this->render("admin/product/list.html.twig", array(
            "products" => $products,
        ));
    }

    /**
     * @Route("/admin/product/{uuid}/delete", name="admin.product.delete")
     * @param Request $request
     * @param $uuid
     * @return Response
     */
    public function adminDeleteProduct(Request $request, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository(ProductionRange::class);

        /**
         * @var ProductionRange $product
         */
        $product = $productRepository->findOneBy(array("uuid" => $uuid));

        if(!$product) return $this->redirectToRoute("admin.product.list");

        $em->remove($product);

        $em->flush();

        return $this->redirectToRoute("admin.product.list");
    }

    /**
     * @Route("/admin/product/{uuid}/images/delete", name="admin.product-images.delete")
     * @param Request $request
     * @param $uuid
     * @return Response
     */
    public function adminDeleteProductAllImages(Request $request, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository(ProductionRange::class);
        $product = $productRepository->findOneBy(array("uuid" => $uuid));

        $imagePaths = $product->getImages();
        foreach ($imagePaths as $imagePath)
            unlink($this->getParameter('kernel.project_dir').'/public'.$imagePath);

        $product->setImages([]);
        $em->persist($product);
        $em->flush();

        return $this->redirectToRoute("admin.product.update", array(
            "uuid" => $uuid
        ));

    }


    /***
     *                 __          _                               __           __  _             ____
     *      ____ _____/ /___ ___  (_)___     ____  _________  ____/ /_  _______/ /_(_)___  ____  / __ \____ _____  ____ ____
     *     / __ `/ __  / __ `__ \/ / __ \   / __ \/ ___/ __ \/ __  / / / / ___/ __/ / __ \/ __ \/ /_/ / __ `/ __ \/ __ `/ _ \
     *    / /_/ / /_/ / / / / / / / / / /  / /_/ / /  / /_/ / /_/ / /_/ / /__/ /_/ / /_/ / / / / _, _/ /_/ / / / / /_/ /  __/
     *    \__,_/\__,_/_/ /_/ /_/_/_/ /_/  / .___/_/   \____/\__,_/\__,_/\___/\__/_/\____/_/ /_/_/ |_|\__,_/_/ /_/\__, /\___/
     *                                   /_/                                                                    /____/
     */

    /**
     * @Route("admin/production-range/{uuid}/update", name="admin.production-range.update", methods={"GET","POST"})
     * @param Request $request
     * @param ProductionRange $product
     * @param FileUploader $fileUploader
     * @param $uuid
     * @return Response
     * @throws Exception
     */
    public function adminUpdateProductionRange(Request $request, ProductionRange $product, FileUploader $fileUploader, $uuid): Response
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository(ProductionRange::class);
        $product = $productRepository->findOneBy(array("uuid" => $uuid));

        if(!$product) return $this->redirectToRoute("admin.production-range.list");

        $form = $this->createForm(ProductionRangeType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
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
                if($dataUrls[$i]){
                    $image = new Base64ToFile($dataUrls[$i], $imageNames[$i]);
                    $contentName = $fileUploader->upload($image);
                    $content[$i]="/uploads/images/".$contentName;
                }
            }
            //要先確定原本有沒有圖片，
            //因為content 是array 如果直接setImages的話原本的圖片會不見
            $imageUrls = $product->getImages();
            if($imageUrls)
                $content = array_merge($imageUrls,$content);

            $product->setImages($content);

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('admin.production-range.list');
        }

        return $this->render('admin/production-range/update.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/production-range/{uuid}/info", name="admin.production-range.fetch", methods={"GET"})
     * @param Request $request
     * @param $uuid
     * @return string
     */
    public function adminFetchProductionRange(Request $request, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository(ProductionRange::class);
        $product = $productRepository->findOneBy(array("uuid" =>$uuid));

        if(!$product) return $this->redirectToRoute("admin.production-range.list");

        return $this->render("admin/production-range/fetch.html.twig", array(
            "product" => $product
        ));
    }

    /**
     * @Route("/admin/production-range", name="admin.production-range.list")
     * @param Request $request
     * @return Response
     */
    public function adminListProductionRange(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository(ProductionRange::class);

        $products = $productRepository->findBy(array("showCase" =>0),array("sort"=>"ASC"));

        return $this->render("admin/production-range/list.html.twig", array(
            "products" => $products,
        ));
    }

    /**
     * @Route("/admin/production-range/{uuid}/delete", name="admin.production-range.delete")
     * @param Request $request
     * @param $uuid
     * @return Response
     */
    public function adminDeleteProductionRange(Request $request, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository(ProductionRange::class);

        /**
         * @var ProductionRange $product
         */
        $product = $productRepository->findOneBy(array("uuid" => $uuid));

        if(!$product) return $this->redirectToRoute("admin.production-range.list");

        $em->remove($product);

        $em->flush();

        return $this->redirectToRoute("admin.production-range.list");
    }

}
