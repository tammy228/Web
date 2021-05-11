<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductToCategory;
use App\Entity\User;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\Base64ToFile;
use App\Service\FileUploader;
use App\Service\ImageToBase64;
use Exception;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
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
     * @Route("admin/product/handleSearch", name="admin.handleSearch")
     * @param Request $request
     * @param $uuid
     * @return Response
     */
    public function adminHandleSearch(Request $request,$uuid)
    {
        $product='';
        $form = $this->createFormBuilder(null)
            ->setAction($this->generateUrl('admin.handleSearch',array('uuid'=>$uuid)))
            ->add('query', TextType::class,array(
                'label'=> '搜尋欄'))
            ->add('search', SubmitType::class,array(
                    'attr' => [
                        'class' => 'btn btn-primary'
                    ]
                )
            )
            ->getForm();

        $em = $this->getDoctrine()->getManager();

        /**
         * @var ProductRepository $productRepository
         */
        $productRepository=$em->getRepository(Product::class);
        $userRepository=$em->getRepository(User::class);
        $user = $userRepository->findOneBy(array('uuid'=>$uuid));
        $query=$_POST['form']['query'];

        $scopes = $productRepository->findBy(array('user'=>$user));
        if($scopes)
        {
            $product = $productRepository->searchProduct($query);
        }

        return $this->render('admin/product/list.html.twig', [
            'query' => $query,
            'products' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/product/create", name="admin.product.create", methods={"GET","POST"})
     * @param UserRepository $userRepository
     * @param FileUploader $fileUploader
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function adminCreateProduct(UserRepository $userRepository,
                                       FileUploader $fileUploader,
                                       ImageToBase64 $imageToBase64,
                                       Packages $assetsManager,
                                       Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

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
            $product->setThumbNail($content);

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

            $product->setImages($content);
            $product->setPrice($_POST['price']);
            $product->setStock($_POST['stock']);
            $product->setSize($_POST['size']);
            $product->setUuid();

            //summernote 刪html tag
            $plainText = strip_tags($form['enDescription']->getData(),'<img><p>');
            $product->setEnDescription($plainText);

            $categoryId = $form['category']->getData();
            $category = $em->getRepository(Category::class)->find($categoryId[0]);
            $productToCategory = new ProductToCategory();
            $productToCategory->setCategory($category);
            $productToCategory->setProduct($product);

            $em->persist($productToCategory);
            $em->persist($product);

            $em->flush();

            return $this->redirectToRoute('admin.product.list');
        }

        return $this->render('admin/product/create.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/product", name="admin.product.list")
     * @param Request $request
     * @return Response
     */
    public function adminListProduct(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository(Product::class);

        $products = $productRepository->findBy(array("deleted" => 0));

        return $this->render("admin/product/list.html.twig", array(
            "products" => $products,
        ));
    }

    /**
     * @Route("admin/category/{id}/product", name="admin.category.list-product", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function adminListProductByCategory(Request $request, $id)
    {
        $em = $this ->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($id);

        $productToCategoryRepository = $em->getRepository(ProductToCategory::class);
        $relations = $productToCategoryRepository->findBy(array(
            "category" => $category
        ));

        $products = [];
        foreach ($relations as $relation)
        {
            /**
             * @var ProductToCategory $relation
             */
            array_push($products, $relation->getProduct());
        }

        return $this -> render("admin/product/list.html.twig", array(
            "products" => $products,
        ));
    }

    /**
     * @Route("admin/product/{uuid}/update", name="admin.product.update", methods={"GET","POST"})
     * @param Request $request
     * @param Product $product
     * @param FileUploader $fileUploader
     * @param $uuid
     * @return Response
     * @throws Exception
     */
    public function adminUpdateProduct(Request $request, Product $product, FileUploader $fileUploader, $uuid): Response
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository(Product::class);
        $product = $productRepository->findOneBy(array("uuid" => $uuid));

        if(!$product) return $this->redirectToRoute("admin.product.list");

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
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
                $product->setThumbNail($content);
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

            //要先確定原本有沒有圖片，
            //因為content 是array 如果直接setImages的話原本的圖片會不見
            $imageUrls = $product->getImages();
            if($imageUrls)
                $content = array_merge($imageUrls,$content);

            $product->setImages($content);

            //summernote 刪html tag
            $plainText = strip_tags($form['enDescription']->getData(),'<img><p>');
            $product->setEnDescription($plainText);

            //處理size stock price, 先清空
            $product->setSize([]);
            $product->setPrice([]);
            $product->setStock([]);

            $em->persist($product);
            $em->flush();

            //再全部寫入
            $product->setSize($_POST['size']);
            $product->setPrice($_POST['price']);
            $product->setStock($_POST['stock']);

            $categoryId = $form['category']->getData();
            $category = $em->getRepository(Category::class)->find($categoryId[0]);

            //unset 原本product 與 category 的關聯
            $relation = $em->getRepository(ProductToCategory::class)->findOneBy(array(
                "product" => $product
            ));
            if($relation)
                $em->remove($relation);

            //建立新關聯
            $productToCategory = new ProductToCategory();
            $productToCategory->setCategory($category);
            $productToCategory->setProduct($product);

            $em->persist($product);
            $em->persist($productToCategory);
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
        $productRepository = $em->getRepository(Product::class);
        $product = $productRepository->findOneBy(array("uuid" =>$uuid));

        if(!$product) return $this->redirectToRoute("admin.product.list");

        return $this->render("admin/product/fetch.html.twig", array(
            "product" => $product
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
        $productRepository = $em->getRepository(Product::class);

        /**
         * @var Product $product
         */
        $product = $productRepository->findOneBy(array("uuid" => $uuid));

        if(!$product) return $this->redirectToRoute("admin.product.list");

        //soft delete
        $product->setDeleted(1);
        $em->persist($product);
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
        $productRepository = $em->getRepository(Product::class);
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
     *
     *      __  __ _____ ___   _____
     *     / / / // ___// _ \ / ___/
     *    / /_/ /(__  )/  __// /
     *    \__,_//____/ \___//_/
     *
     */

    /**
     * @Route("child/category/{id}/product", name="user.product.list",requirements={"id"="\d+"})
     * @param $id
     * @return Response
     */
    public function listProduct($id)
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var Category $childCategory
         */
        $childCategory = $em->getRepository(Category::class)->find($id);
        $childCategories = $em->getRepository(Category::class)->findBy(array(
            "parent" => $childCategory->getParent()
        ));

        $productToCategoryRepository = $em->getRepository(ProductToCategory::class);
        $relations = $productToCategoryRepository->findBy(array(
            "category" => $childCategory
        ));

        $products = [];
        foreach ($relations as $relation)
        {
            array_push($products, $relation->getProduct());
        }
        return $this->render("user/product/listBy.html.twig", array(
            "products" => $products,
            "childCategories" => $childCategories
        ));
    }

    /**
     * @Route("product/{uuid}/info", name="user.product.fetch")
     * @param $uuid
     * @return Response
     */
    public function fetchProduct($uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository(Product::class);
        $product = $productRepository->findOneBy(["uuid" => $uuid]);

        if(!$product) return $this->redirectToRoute("user.product.list");

        return $this->render("user/product/fetch.html.twig",[
            "product" => $product
        ]);
    }

}
