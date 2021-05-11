<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\News;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\UserToUser;
use App\Form\UserType;
use App\Repository\CategoryRepository;
use App\Repository\NewsRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Repository\UserToUserRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

class WebsiteController extends AbstractController
{
    /**
     * @Route("/aboutUs", name="aboutUs")
     */
    public function aboutUs()
    {
        return $this->render('user/website/aboutUs.html.twig');
    }

    /**
     * @Route("/applicationCompleted", name="applicationCompleted")
     */
    public function applicationCompleted()
    {
        return $this->render('user/website/applicationCompleted.html.twig');
    }

    /**
     * @Route("/team", name="team")
     */
    public function team()
    {
        return $this->render('user/website/team.html.twig');
    }


    /**
     * @Route("/memberFAQ", name="memberFAQ")
     */
    public function memberFAQ()
    {
        return $this->render('user/website/memberFAQ.html.twig');
    }

    /**
     * @Route("/productFAQ", name="productFAQ")
     */
    public function productFAQ()
    {
        return $this->render('user/website/productFAQ.html.twig');
    }

    /**
     * @Route("/shoppingFAQ", name="shoppingFAQ")
     */
    public function shoppingFAQ()
    {
        return $this->render('user/website/shoppingFAQ.html.twig');
    }

    /**
     * @Route("/deliveryNote", name="deliveryNote")
     */
    public function deliveryNote()
    {
        return $this->render('user/website/deliveryNote.html.twig');
    }

    /**
     * @Route("/returnInstructions", name="returnInstructions")
     */
    public function returnInstructions()
    {
        return $this->render('user/website/returnInstructions.html.twig');
    }

    /**
     * @Route("/terms", name="terms")
     */
    public function terms()
    {
        return $this->render('user/website/terms.html.twig');
    }

    /**
     * @Route("/privacy", name="privacy")
     */
    public function privacy()
    {
        return $this->render('user/website/privacy.html.twig');
    }

    /**
     * @Route("/consumer", name="consumer")
     */
    public function consumer()
    {
        return $this->render('user/website/consumer.html.twig');
    }

    /**
     * @Route("/responsibility", name="responsibility")
     */
    public function responsibility()
    {
        return $this->render('user/website/responsibility.html.twig');
    }

    /**
     * @Route("/platformTrading", name="platformTrading")
     */
    public function platformTrading()
    {
        return $this->render('user/website/platformTrading.html.twig');
    }

    /**
     * @Route("/message", name="message")
     */
    public function message()
    {
        return $this->render('user/website/message.html.twig');
    }

    /**
     * @Route("/sendMessage", name="sendMessage", methods={"POST"})
     */
    public function sendMessage(Request $request)
    {
        $msg = new Message();
        $msg->setName($_POST['name']);
        $msg->setEmail($_POST['email']);
        $msg->setMobile($_POST['mobile']);
        $msg->setContent($_POST['content']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($msg);
        $em->flush();

        return $this->render('user/website/sendMessage.html.twig');
    }



    /**
     * @Route("/news", name="news.list")
     * @param NewsRepository $newsRepository
     * @return Response
     */
    public function newsList(NewsRepository $newsRepository)
    {
        $news = $newsRepository->findBy(array(),array('updateAt'=>'DESC'));
        return $this->render('user/news/list.html.twig',[
            'news' => $news,
        ]);
    }

    /**
     * @Route("/news/{uuid}",  name="news.fetch")
     * @param NewsRepository $newsRepository
     * @param $uuid
     * @return Response
     */
    public function newsFetch(NewsRepository $newsRepository, $uuid)
    {
        return $this->render('user/news/fetch.html.twig',[
            'news' => $newsRepository->findOneBy(array('uuid'=>$uuid)),
        ]);
    }

    /**
     * @Route("/newFarmer", name="farmer.list")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function farmerList(UserRepository $userRepository)
    {
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $qb->select('u')
            ->from(User::class, 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"ROLE_FARMER"%');
        $farmers = $qb->getQuery()->getResult();
        $limit = count($farmers)/8;

        return $this->render('user/news/farmerList.html.twig',[
            'farmers' => $farmers,
            'limit' => $limit,
        ]);
    }

    /**
     * @Route("/products", name="products.list")
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function productList(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();
        return  $this->render('user/product/list.html.twig',[
            'products' => $products,
            'title' => '所有商品'
        ]);
    }

    /**
     * @Route("/products/{slug}", name="products.list.by")
     * @param ProductRepository $productRepository
     * @param $slug
     * @return Response
     */
    public function productListBy(ProductRepository $productRepository, $slug)
    {
        $products = $productRepository->findBy(array($slug=>true));
        if($slug == 'onSale')
        {
            $title = '限時優惠';
        }
        elseif( $slug == 'expired')
        {
            $title = '即期品';
        }
        else
        {
            $title = '團購';
        }
        return  $this->render('user/product/list.html.twig',[
            'products' => $products,
            'title' => $title
        ]);
    }



    /**
     * @Route("/products/cate/{slug}", name="products.list.by.category")
     * @param CategoryRepository $categoryRepository
     * @param $slug
     * @return Response
     */
    public function productListByCategory(CategoryRepository $categoryRepository, $slug)
    {
        $category = $categoryRepository->findOneBy(array('uuid'=>$slug));
        $products = $category->getProducts();
        return  $this->render('user/product/listBy.html.twig',[
            'products' => $products,
            'title' => $category->getName(),
        ]);
    }

    /**
     * @Route("/product/{uuid}",  name="product.fetch")
     * @param ProductRepository $productRepository
     * @param string $uuid
     * @return Response
     */
    public function productFetch(ProductRepository $productRepository,string $uuid)
    {
        $product = $productRepository->findOneBy(array('uuid'=>$uuid));
        return $this->render('user/product/fetch.html.twig',[
            'product' => $product,
        ]);
    }

    /**
     * @Route("/instruction/farmer/{uuid}", name="instruction.farmer")
     * @param $uuid
     * @param UserRepository $userRepository
     * @return Response
     */
    public function instructionFarmer($uuid, userRepository $userRepository)
    {
        $user = $userRepository->findOneBy(array('uuid'=>$uuid));
        return  $this->render('user/website/farmIntroduction.html.twig',[
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/{uuid}",  name="user.info")
     * @param $uuid
     * @param UserRepository $userRepository
     * @param Request $request
     * @return Response
     */
    public function userInfo($uuid, UserRepository $userRepository, Request $request){
        $form = $this->createForm(UserType::class,null);
        $form->handleRequest($request);

        return $this->render('user/user/info.html.twig',[
            'user' => $userRepository->findOneBy(array('uuid'=>$uuid)),
            'form' =>$form->createView(),

        ]);
    }

    /**
     * @Route("/user/{uuid}/update", name="user.update", methods={"POST"})
     * @param $uuid
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function userUpdate($uuid, Request $request, UserRepository $userRepository)
    {
        $user = $userRepository->findOneBy(array('uuid'=>$uuid));
        if(!$user)
            return $this->redirectToRoute('user.index');
        $birth = $_POST['birthY'].'/'.$_POST['birthM'].'/'.$_POST['birthD'];
        if(!checkdate($_POST['birthM'],$_POST['birthD'],$_POST['birthY']))
        {
            return new Response('<script>alert(\'日期錯誤\');window.history.back(-1);</script>');
        }

        $user->setName($_POST['name']);
        $user->setMobile($_POST['mobile']);
        $user->setSexual($_POST['sex']);


        $datetime = DateTime::createFromFormat("Y/m/d", $birth);
        $datetime->format("Y-m-d");
        $user ->setBirthday($datetime);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('user.info',array('uuid'=>$uuid));
    }

    /**
     * @Route("/order/{uuid}", name="user.order")
     * @param $uuid
     * @param UserRepository $userRepository
     * @return Response
     */
    public function userOrder($uuid ,UserRepository $userRepository)
    {
        $user = $userRepository->findOneBy(array('uuid'=>$uuid));
        $orders = $user->getUserOrders();

        return $this->render('user/order/list.html.twig',array(
            'orders' => $orders,
        ));
    }

    /**
     * @Route("/user/follow/farmer/{productId}", name="user.follow.farmer", requirements={"productId"="\d+"})
     * @param $productId
     * @return Response
     */
    public function userFollowFarmer($productId)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($productId);

        $farmer = $product->getUser();
        $user = $this->getUser();

        $userToUserRepo = $em->getRepository(UserToUser::class);
        $relation = $userToUserRepo->findOneBy(array(
            "user" => $user,
            "farmer" => $farmer
        ));

        if($relation){
            $em->remove($relation);
        }else{
            $userToUser = new UserToUser();
            $userToUser->setFarmer($farmer);
            $userToUser->setUser($user);
            $em->persist($userToUser);
        }
        $em->flush();

        return $this->redirectToRoute("product.fetch",array(
            "uuid" => $product->getUuid()
        ));
    }

    /**
     * @Route("/user/follow/farmer/list", name="user.follow.farmer.list")
     * @param UserToUserRepository $userToUserRepository
     * @return Response
     */
    public function listFollowFarmer(UserToUserRepository $userToUserRepository)
    {
        $relations = $userToUserRepository->findBy(array(
            "user" => $this->getUser()
        ));

        $farmers = [];
        foreach ($relations as $relation)
            array_push($farmers, $relation->getFarmer());
        return $this->render('/user/user/list-follow-farmer.hml.twig',array(
            "farmers" => $farmers
        ));
    }


}
