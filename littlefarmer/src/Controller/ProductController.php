<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Report;
use App\Entity\User;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Exception;
use SendGrid\Mail\TypeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \SendGrid\Mail\Mail;

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
     * @Route("/admin/farmer/{uuid}/product", name="admin.product.list")
     * @param string $uuid
     * @param ProductRepository $productRepository
     * @param UserRepository $userRepository
     * @return Response
     */
    public function adminProductList($uuid, ProductRepository $productRepository,UserRepository $userRepository)
    {

        $form = $this->createFormBuilder(null)
            ->setAction($this->generateUrl('admin.handleSearch',array('uuid'=>$uuid)))
            ->add('query', TextType::class,array(
                'label'=> '搜尋欄'))
            ->add('search', SubmitType::class,array(
                'attr' => [
                    'label' => '搜尋',
                    'class' => 'btn btn-primary'
                ]
            ))
            ->getForm();
        $farmer = $userRepository->findOneBy(array('uuid'=>$uuid));
        $products = $farmer->getProducts();

        return $this->render('admin/product/list.html.twig',array(
            'products' => $products,
            'query' => '',
            'form' => $form->createView(),
            'farmer' => $farmer,
        ));
    }

    /**
     * @Route("admin/product/handleSearch", name="admin.handleSearch")
     * @param Request $request
     * @return Response
     */
    public function adminHandleSearch( Request $request,$uuid)
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
        $productRepository=$em->getRepository(Product::class);
        $userRepository=$em->getRepository(User::class);
        $user = $userRepository->findOneBy(array('uuid'=>$uuid));
        $query=$_POST['form']['query'];
        $uuid=$this->getUser()->getUuid();

        $scopes = $productRepository->findBy(array('user'=>$user));
        if($scopes)
        {
            $product = $productRepository->searchProduct($query);
        }

        return $this->render('farmer/product/list.html.twig', [
            'query' => $query,
            'products' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/product/{uuid}", name="admin.product.fetch", methods={"GET"})
     * @param ProductRepository $productRepository
     * @param $uuid
     * @return Response
     */
    public function adminProductFetch(ProductRepository $productRepository,$uuid): Response
    {
        $product = $productRepository->findOneBy(array(
            "uuid" => $uuid
        ));
        return $this->render('admin/product/fetch.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("admin/product/{id}/delete", name="admin.product.delete",requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function adminSoftDelete(Request $request, $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository(Product::class);
        $product = $productRepository->find($id);

        if(!$product) return $this->redirectToRoute('admin.product.list', array(
            'uuid'=>$this->getUser()->getUuid()
        ));

        $product->setDeleted(true);
        $em->persist($product);
        $em->flush();

        return $this->redirectToRoute('admin.product.list', array('uuid'=> $product->getUser()->getUuid()));
    }

    /***
     *        ______
     *       / ____/____ _ _____ ____ ___   ___   _____
     *      / /_   / __ `// ___// __ `__ \ / _ \ / ___/
     *     / __/  / /_/ // /   / / / / / //  __// /
     *    /_/     \__,_//_/   /_/ /_/ /_/ \___//_/
     *
     */
    /**
     * @Route("/farmer/{uuid}/product", name="farmer.product.list")
     * @param string $uuid
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function farmerProductList($uuid, ProductRepository $productRepository)
    {

        $form = $this->createFormBuilder(null)
            ->setAction($this->generateUrl('farmer.handleSearch'))
            ->add('query', TextType::class,array(
                'label'=> '搜尋欄'))
            ->add('search', SubmitType::class,array(
                    'attr' => [
                        'label' => '搜尋',
                        'class' => 'btn btn-primary'
                    ]
                ))
            ->getForm();

        $products = $this->getUser()->getProducts();
        return $this->render('farmer/product/list.html.twig',array(
            'products' => $products,
            'query' => '',
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("farmer/product/handleSearch", name="farmer.handleSearch")
     * @param Request $request
     * @return Response
     */
    public function farmerHandleSearch( Request $request)
    {
        $product='';
        $form = $this->createFormBuilder(null)
            ->setAction($this->generateUrl('farmer.handleSearch'))
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
        $productRepository=$em->getRepository(Product::class);
        $query=$_POST['form']['query'];
        $uuid=$this->getUser()->getUuid();

        $scopes = $productRepository->findBy(array('user'=>$this->getUser()));
        if($scopes)
        {
            $product = $productRepository->searchProduct($query);
        }

        return $this->render('farmer/product/list.html.twig', [
            'query' => $query,
            'products' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("farmer/{uuid}/product/create", name="farmer.product.create", methods={"GET","POST"})
     * @param UserRepository $userRepository
     * @param FileUploader $fileUploader
     * @param Request $request
     * @param $uuid
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function farmerProductCreate(UserRepository $userRepository, FileUploader $fileUploader, Request $request, $uuid)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            //setImage
            $i = 0;
            $content=[];
            $images = $form['image']->getData();
            /**
             * @var UploadedFile $image
             */
            foreach ($images as $image) {

                $contentName = $fileUploader->upload($image);

                $content[$i]="/uploads/images/".$contentName;
                $i++;
            }
            $product->setImages($content);

            //setCategory
            $categoryRepository = $em->getRepository(Category::class);
            $category = $categoryRepository->find($_POST['category']);
            $product->setCategory($category);

            $product->setUuid();

            $user = $userRepository->findOneBy(array('uuid'=>$uuid));
            $product->setUser($user);

            $em->persist($user);
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('farmer.product.list',array('uuid'=>$uuid));
        }

        return $this->render('farmer/product/create.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("farmer/product/{uuid}", name="farmer.product.fetch", methods={"GET"})
     * @param ProductRepository $productRepository
     * @param $uuid
     * @return Response
     */
    public function farmerProductFetch(ProductRepository $productRepository,$uuid): Response
    {
        $product = $productRepository->findOneBy(array(
            "uuid" => $uuid
        ));
        return $this->render('farmer/product/fetch.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("farmer/product/{uuid}/update", name="farmer.product.update", methods={"GET","POST"})
     * @param Request $request
     * @param Product $product
     * @param FileUploader $fileUploader
     * @param $uuid
     * @return Response
     * @throws Exception
     */
    public function update(Request $request, Product $product, FileUploader $fileUploader, $uuid): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $content=$product->getImages();
            $images =$form['image']->getData();

            $i = 0;

            /**
             * @var UploadedFile $image
             */
            foreach ($images as $image) {

                $contentName = $fileUploader->upload($image);

                $content[$i]="/uploads/images/".$contentName;
                $i++;
            }

            $product->setImages($content);
            if($_POST['category'] != 0)
            {
                $em = $this->getDoctrine()->getManager();
                $categoryRepository = $em->getRepository(Category::class);
                $category = $categoryRepository->find($_POST['category']);
                $category->addProduct($product);
                $em->persist($category);
            }

            $product->setUpdateAt();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('farmer.product.list',array('uuid'=>$uuid));
        }

        return $this->render('farmer/product/update.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("farmer/product/{id}/delete", name="farmer.product.delete",requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function farmerSoftDelete(Request $request, $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository(Product::class);
        $product = $productRepository->find($id);

        if(!$product) return $this->redirectToRoute('farmer.product.list',array(
            'uuid'=>$this->getUser()->getUuid()
        ));

        $product->setDeleted(true);
        $em->persist($product);
        $em->flush();

        return $this->redirectToRoute('farmer.product.list',array('uuid'=> $this->getUser()->getUuid()));
    }

    /***
     *       __  __
     *      / / / /_____ ___   _____
     *     / / / // ___// _ \ / ___/
     *    / /_/ /(__  )/  __// /
     *    \____//____/ \___//_/
     *
     */

    /**
     * @Route("user/product/handleSearch", name="user.handleSearch")
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function userHandleSearch(Request $request, ProductRepository $productRepository)
    {
        $products = $productRepository->searchProduct($_POST['search']);
        return $this->render('user/product/list.html.twig', [
            'title' => '搜尋名稱:'.$_POST['search'],
            'products' => $products,
        ]);
    }

    /**
     * @Route("user/product/report", name="user.product.report")
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function report(ProductRepository $productRepository)
    {
        $id = $_GET['x'];
        $number = $_GET['q'];

        $product = $productRepository->findOneBy(array('uuid'=>$id));

        if(!$product)
        {
            return new Response('<script>alert(\'無此產品\');</script>');
        }
        $farmer = $product->getUser();

        $QRs = $product->getQRCode();

        if(count($QRs)<$number)
        {
            return new Response('<script>alert(\'QR碼錯誤\');</script>');
        }
        if($QRs[$number]==1)
        {
            return new Response('<script>alert(\'QR碼已被使用\');</script>');
        }

        return $this->render('user/product/report.html.twig',array(
            'id' => $id,
            'number' => $number,
            'product' => $product,
            'farmer' => $farmer,

        ));

    }

    /**
     * @Route("user/product/report/up", name="user.product.report.up")
     * @param ProductRepository $productRepository
     * @return Response
     * @throws Exception
     */
    public function reportUp(ProductRepository $productRepository)
    {
        $id = $_POST['x'];
        $number = $_POST['q'];
        $product = $productRepository->findOneBy(array('uuid'=>$id));

        $report = new Report();
        $report->setCreateAt();
        $report->setName($_POST['name']);
        $report->setProduct($product);
        $report->setContent($_POST['email']);
        $report->setMobile($_POST['mobile']);

        $QRs = $product->getQRCode();
        $QRs[$number] = 1;

        $product->editQRCode($QRs);

        $em =$this->getDoctrine()->getManager();
        $em->persist($product);
        $em->persist($report);
        $em->flush();

        return $this->redirectToRoute('user.index');
    }


}
