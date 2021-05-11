<?php

namespace App\Controller;

use App\Entity\MessageOrEvaluation;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\EvaluationType;
use App\Form\MessageType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\MessageOrEvaluationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \SendGrid\Mail\Mail;
use App\Service\FileUploader;


class ProductController extends AbstractController
{


    /**
     *     _      _       _
     *    /_\  __| |_ __ (_)_ _
     *   / _ \/ _` | '  \| | ' \
     *  /_/ \_\__,_|_|_|_|_|_||_|
     */

    /**
     * @Route("admin/product", name="admin.product.list", methods={"GET"})
     */
    public function adminProductList(ProductRepository $productRepository): Response
    {

        $form = $this->createFormBuilder(null)
            ->setAction($this->generateUrl('admin.handleSearch'))
            ->add('query', TextType::class,array(
                'label'=> '搜尋欄'))
            ->add('search', SubmitType::class,array(
                    'attr' => [
                        'label' => '搜尋',
                        'class' => 'btn btn-primary'
                    ]
                )
            )
            ->getForm();

        return $this->render('admin/product/list.html.twig', [
            'query' => '',
            'products' => $productRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/product/handleSearch", name="admin.handleSearch")
     * @param Request $request
     */
    public function adminHandleSearch( Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->setAction($this->generateUrl('admin.handleSearch'))
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
        $product = $productRepository->findBy(array('name'=>$query));
        return $this->render('admin/product/list.html.twig', [
            'query' => $query,
            'products' => $product,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("admin/product/create", name="admin.product.create", methods={"GET","POST"})
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function create(Request $request, FileUploader $fileUploader): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        $content[] ='';
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form['image']->getData();

            $i = 0;

            /**
             * @var UploadedFile $image
             */
            foreach ($images as $image) {

                $contentName = $fileUploader->upload($image);

                $content[$i]="/uploads/photos/".$contentName;
                $i++;
            }
            $product->setPrice($_POST['price']);
            $product->setStock($_POST['stock']);
            $product->setFormat($_POST['format']);

            if($_POST['category'] != 0)
            {
                $em = $this->getDoctrine()->getManager();
                $categoryRepository = $em->getRepository(Category::class);
                $category = $categoryRepository->find(array('id' => $_POST['category']));
                $category->addProduct($product);
                $em->persist($category);
            }
            $product->setImage($content);



            $now = new \Datetime('now + 8hours');
            $product -> setCreateAt($now);
            $product -> setUpdateAt($now);
            $em = $this->getDoctrine()->getManager();
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
     * @Route("admin/product/{id}", name="admin.product.fetch", methods={"GET"})
     */
    public function show(Product $product, MessageOrEvaluationRepository $messageOrEvaluationRepository): Response
    {
        $messages = $messageOrEvaluationRepository->findBy(array('product'=>$product,'MessageOrEvaluation'=>true),array('create_at'=>'DESC'));
        $evaluations = $messageOrEvaluationRepository->findBy(array('product'=>$product,'MessageOrEvaluation'=>false),array('create_at'=>'DESC'));
        return $this->render('admin/product/fetch.html.twig', [
            'product' => $product,
            'messages' => $messages,
            'evaluations' => $evaluations,
        ]);
    }

    /**
     * @Route("admin/product/{id}/update", name="admin.product.update", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function update(Request $request, Product $product, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $content=$product->getImage();
            $images =$form['image']->getData();

            $formats = $product->getFormat();
            $price = $product->getPrice();
            $stock = $product->getStock();
            /**
             * 檢查同規格商品是否降價
             * 若降價，則寄出通知信
             */
            foreach ($formats as $oldKey => $format)
            {
                foreach ($_POST['format'] as $newKey => $newFormat)
                {
                    if($format == $newFormat and $price[$oldKey] > $_POST['price'][$newKey])
                    {
                        $users = $product->getUsers();
                        foreach ($users as $user)
                        {
                            $email= new Mail();
                            $email->setFrom("shopping@example.com", "購物網");
                            $email->setSubject('降價通知');
                            $email->addTo($user->getEmail(),'user');
                            $email->addContent(
                                "text/html", "降價產品 :".$product->getName()."http://localhost:8003/product/".$product->getId()
                            );

                            $sendgrid = new \SendGrid($_ENV['SENDGRID_KEY']);

                            $sendgrid->send($email);
                        }
                    }
                }
            }

            if($images){
                $i = count($content);
                if($i != 0)
                    $i++;

                /**
                 * @var UploadedFile $image
                 */
                foreach ($images as $image) {
                    $contentName = $fileUploader->upload($image);
                    $content[$i]="/uploads/photos/".$contentName;
                    $i++;
                }
            }
            /**
             * 清除重複規格
             */
            $write_stock=[];
            $write_price=[];
            $unique_format = array_unique ($_POST['format']);
            foreach ($unique_format as $key => $value)
            {
                foreach ($_POST['format'] as $newKey => $newValue)
                {
                    if($value == $newValue)
                    {
                        $write_stock[$key]=$_POST['stock'][$newKey];
                        $write_price[$key]=$_POST['price'][$newKey];
                    }
                }
            }

            $product->setPrice($write_price);
            $product->setStock($write_stock);
            $product->setFormat($unique_format);
            $product->setImage($content);
            if($_POST['category'] != 0)
            {
                $em = $this->getDoctrine()->getManager();
                $categoryRepository = $em->getRepository(Category::class);
                $category = $categoryRepository->find(array('id' => $_POST['category']));
                $category->addProduct($product);
                $em->persist($category);
            }

            $now = new \Datetime('now + 8hours');
            $product -> setUpdateAt($now);

            $em = $this->getDoctrine()->getManager();
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
     * @Route("admin/product/{id}/clean", name="admin.product.cleanimage", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function cleanImage(Request $request, Product $product, FileUploader $fileUploader): Response
    {
        $images = $product->getImage();
        $product->setImage([]);
        foreach ($images as $image)
        {
            unlink($this->getParameter('kernel.project_dir').'/public'.$image);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return $this->redirectToRoute('admin.product.update', array(
            "id" => $product->getId()
        ));
    }

    /**
     * @Route("admin/product/{id}/delete", name="admin.product.delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function delete(Request $request, $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository(Product::class);
        $product = $productRepository->find($id);

        if(!$product) return $this->redirectToRoute("admin.product.list");

        $em->remove($product);

        $em->flush();

        return $this->redirectToRoute('admin.product.list');
    }

    /**
     *     _      _   _
     *    /_\  __| |_(_)___ _ _  ___
     *   / _ \/ _|  _| / _ \ ' \(_-<
     *  /_/ \_\__|\__|_\___/_||_/__/
     */

    /**
     * @Route("product", name="product.list")
     */
    public function productList(ProductRepository $productRepository): Response
    {

        $form = $this->createFormBuilder(null)
            ->setAction($this->generateUrl('handleSearch'))
            ->add('query', TextType::class,array(
                'label'=> '搜尋欄',
                'attr' => [
                    'placeholder'=> "請輸入產品名..."
                ]
            ))
            ->add('search', SubmitType::class,array(
                    'attr' => [
                        'class' => 'btn btn-primary'
                    ]
                )
            )
            ->getForm();

        return $this->render('product/list.html.twig', [
            'query' => '',
            'products' => $productRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("product/handleSearch", name="handleSearch")
     * @param Request $request
     */
    public function handleSearch( Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->setAction($this->generateUrl('handleSearch'))
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
        $product = $productRepository->findBy(array('name'=>$query));
        return $this->render('product/list.html.twig', [
            'query' => $query,
            'products' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("product/{id}/", name="product.fetch", requirements={"id"="\d+"})
     */
    public function productFetch($id, ProductRepository $productRepository, MessageOrEvaluationRepository $messageOrEvaluationRepository,Request $request): Response
    {
        $new = new MessageOrEvaluation();
        $em = $this->getDoctrine()->getManager();
        $product = $productRepository->find($id);
        $messages = $messageOrEvaluationRepository->findBy(array('product'=>$product,'MessageOrEvaluation'=>true),array('create_at'=>'DESC'));
        $evaluations = $messageOrEvaluationRepository->findBy(array('product'=>$product,'MessageOrEvaluation'=>false),array('create_at'=>'DESC'));

        if($this->getUser())
        {
            $user = $this->getUser()->getName();

            $form = $this->createForm(MessageType::class);
            $form -> handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $content=$form['content']->getData();
                $new->setContent($content);
                $new->setMessageOrEvaluation(true);
                $new->setUsername($user);
                $new->setCreateAt(new \Datetime('now + 8hours'));
                $new->setUpdateAt(new \Datetime('now + 8hours'));
                $product->addMoe($new);

                $em->persist($product);
                $em->persist($new);
                $em->flush();

                return $this->redirectToRoute('product.fetch', array(
                    'id' => $id,
                ));
            }
            return $this->render('product/fetch.html.twig', [
                'form' => $form->createView(),
                'product' => $product,
                'messages' => $messages,
                'new' => $new,
                'id'=>$id,
                'error'=>'',
                'evaluations'=>$evaluations,
            ]);
        }
        return $this->render('product/fetch.html.twig', [
            'form' => null,
            'product' => $product,
            'messages' => $messages,
            'new' => $new,
            'id'=>$id,
            'error'=>'',
            'evaluations'=>$evaluations,
        ]);

    }


    /**
     * @Route("product/{id}/follow",name="user.follow", requirements={"id"="\d+"})
     * @param $id
     * @return Response
     */
    public function productFollow($id, ProductRepository $productRepository)
    {
        if(!$this->getUser())
        {
            echo "<script>alert('請先登入');window.location = 'http://localhost:8003/login/';</script>";
            return true;
        }

        $product = $productRepository->find($id);
        $user = $this->getUser();
        $check=true;

        $users=$product->getUsers();
        foreach($users as $u)
        {
            if($u == $user)
            {
                $check = false;
            }
        }
        if($check == true)
        {
            $user->addProducts($product);
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->persist($user);
            $em->flush();

            echo "<script>alert('成功追蹤');window.history.back(-1);</script>";
            return true;
        }
        else
        {
            echo "<script>alert('已追蹤');window.history.back(-1);</script>";
            return true;
        }
    }

    /**
     * @Route("product/follow/list", name="product.follow.list")
     */
    public function productFollowList()
    {
        $user = $this->getUser();


        $products=$user->getProduct();


        return $this->render('/product/follow.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * @Route("product/follow/{id}/clean", name="product.clean.follow", requirements={"id"="\d+"})
     */
    public function cleanFollow(ProductRepository $productRepository, $id)
    {
        $product = $productRepository->find($id);

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        /**
         * 取得資料庫名
         */
        $databaseName = $_ENV['DATABASE_URL'];

        $databaseName = strrchr($databaseName,'/');
        $databaseName = strtok($databaseName,'?');
        $databaseName = trim($databaseName,'/');

        /**
         * 以SOL語法清除jointable關聯
         */
        $RAW_QUERY = 'DELETE FROM `'.$databaseName.'`.`user_product`  WHERE product_id = '.$product->getId().' AND user_id = '.$user->getId().';';

        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

        return $this->redirectToRoute('product.follow.list');
    }

    /**
     * @Route("product/{id}/evaluation", name="product.evaluation", requirements={"id"="\d+"})
     */
    public function productEvaluation($id, Request $request, ProductRepository $productRepository, MessageOrEvaluationRepository $messageOrEvaluationRepository)
    {
        $check=true;
        $em = $this->getDoctrine()->getManager();
        $product = $productRepository->find($id);
        $username = $this->getUser()->getName();

        $moe = $messageOrEvaluationRepository->findOneBy(array('product'=>$product,'MessageOrEvaluation'=>false,'username'=>$username));
        if(isset($moe))
        {
            $moeId = $moe->getId();
        }

        if(!isset($moe))
        {
            $moe = new MessageOrEvaluation();
            $check=false;
            $moeId = 0;
        }

        $user = $this->getUser()->getName();

        $form = $this->createForm(EvaluationType::class,$moe);
        $form -> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $content=$form['content']->getData();
            $moe->setContent($content);
            $moe->setMessageOrEvaluation(false);
            $moe->setUsername($user);
            $moe->setCreateAt(new \Datetime('now + 8hours'));
            $moe->setUpdateAt(new \Datetime('now + 8hours'));
            $product->addMoe($moe);

            $em->persist($product);
            $em->persist($moe);
            $em->flush();

            return $this->redirectToRoute('product.fetch', array(
                'id' => $id,
            ));
        }

        return $this->render('product/evaluation.html.twig', [
            'form' => $form->createView(),
            'new' => $moe,
            'id'=>$id,
            'error'=>'',
            'check'=>$check,
            'moeId' => $moeId,
            'product' => $product,

        ]);
    }


    /**
     * @Route("product/{id}/evaluation/delete", name="product.evaluation.delete", requirements={"id"="\d+"})
     */
    public function productEvaluationDelete($id, Request $request, MessageOrEvaluationRepository $messageOrEvaluationRepository)
    {

        $moe = $messageOrEvaluationRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($moe);
        $em->flush();


        return $this->redirectToRoute('order.list');
    }

    /**
     * @Route("product/{id}/list", name="product.list.category", requirements={"id"="\d+"})
     */
    public function productListByCategory(Request $request, $id)
    {
        /**
         * page data handler
         */
        $page = $request->query->get('page', "1");
        $page = preg_match("/^[0-9]+$/", $page) ? intval($page) : 1;

        /**
         * limit data handler
         */
        $limit = $request->query->get('limit', "0");
        $limit = preg_match("/^[0-9]+$/", $limit) ? intval($limit) : 0;

        $em = $this->getDoctrine()->getManager();

        $categoryRepository = $em->getRepository(Category::class);
        $category = $categoryRepository->find($id);

        $sonCategory = $category->getChildren();

        if(!$category) return $this->redirectToRoute("admin.photos.list");

        $products = $category->getProduct();
        $count = count($products);
        $maxPage = $limit ? ceil($count / $limit) : 1;

        return $this->render("product/listByCategory.html.twig", array(
            "page" => $page,
            "limit" => $limit,
            "maxPage" => $maxPage,
            "count" => $count,
            "products" => $products,
            "category" => $category,
            "id" => $id,
            "sonCategory" => $sonCategory,
        ));
    }
}
