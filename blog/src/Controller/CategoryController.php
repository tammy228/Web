<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Security\LoginFormAuthenticator;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface ;
use App\Repository\UserRepository;

class CategoryController extends AbstractController
{
    /**
     *     _      _   _
     *    /_\  __| |_(_)___ _ _  ___
     *   / _ \/ _|  _| / _ \ ' \(_-<
     *  /_/ \_\__|\__|_\___/_||_/__/
     */

    /**
     * @Route("user/categories/{id}/articles", name="category.list_articles", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function articleListByCategory(Request $request, $id)
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
        $category = $categoryRepository->find(intval($id));

        $sonCategory = $category->getChildren();

        if(!$category) return $this->redirectToRoute("user.index");

        $articles = $category->getArticles();
        $count = count($articles);
        $maxPage = $limit ? ceil($count / $limit) : 1;
        $now = new \DateTime("now + 8 hours");

        return $this->render("article/listbycategory.html.twig", array(
            "page" => $page,
            "limit" => $limit,
            "maxPage" => $maxPage,
            "count" => $count,
            "articles" => $articles,
            "category" => $category,
            "now" => $now,
            'sonCategory' => $sonCategory,
            'id' => $id,
        ));
    }


    /**
     *     _      _       _
     *    /_\  __| |_ __ (_)_ _
     *   / _ \/ _` | '  \| | ' \
     *  /_/ \_\__,_|_|_|_|_|_||_|
     */

    /**
     * @Route("admin/category/create", name="admin.category.create")
     * @param Request $request
     * @return Response
     */
    public function categoryCreate(Request $request)
    {
        $category = New Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form -> handleRequest($request);

        if($form->isSubmitted() && $form-> isValid()){
            $category->setName($form->get('name')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirect('/admin/category');
        }
        return $this->render('admin/category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/category/{id}/article", name="admin.category.list_article", requirements={"id"="\d+"})
     * @param Request $reqest
     * @param $id
     * @return Response
     */
    public function adminCategoryArticle(Request $request, $id)
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

        /**
         * @var AlbumRepository $albumRepository
         */
        $categoryRepository = $em->getRepository(Category::class);
        $category = $categoryRepository->find($id);

        $sonCategory = $category->getChildren();

        if(!$category) return $this->redirectToRoute("admin.photos.list");

        $articles = $category->getArticles();
        $count = count($articles);
        $maxPage = $limit ? ceil($count / $limit) : 1;

        return $this->render("admin/category/listarticle.html.twig", array(
            "page" => $page,
            "limit" => $limit,
            "maxPage" => $maxPage,
            "count" => $count,
            "articles" => $articles,
            "category" => $category,
            "id" => $id,
            "sonCategory" => $sonCategory,
        ));
    }

    /**
     * @Route("/admin/category/{id}", name="admin.soncategory.list", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function adminSonCategoryList(Request $request, $id)
    {
        $entityManager = $this ->getDoctrine()->getManager();
        $categoryRepository = $entityManager->getRepository(Category::class);

        $parentCategory = $categoryRepository->find($id);
        $Categories = $categoryRepository->findBy(array('parent' => $id));

        return $this -> render("admin/category/sonlist.html.twig", array(
            "categories" => $Categories,
            "parentCategory" => $parentCategory,

        ));
    }

    /**
     * @Route("admin/category", name="admin.category.list")
     * @param Request $request
     * @return Response
     */
    public function adminCategoryList(Request $request)
    {
        $entityManager = $this ->getDoctrine()->getManager();
        $categoryRepository = $entityManager->getRepository(Category::class);

        $Categories = $categoryRepository->findBy(array('parent'=> NULL ), array("id" => "ASC"));

        return $this -> render("admin/category/list.html.twig", array(
            "categories" => $Categories,
        ));
    }

    /**
     * @Route("/admin/category/{id}/delete", name="admin.category.delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function adminCategoryDelete(Request $request, $id)
    {
        $categoryId = intval($id);
        $entityManager = $this->getDoctrine()->getManager();
        $categoryRepository = $entityManager->getRepository(Category::class);

        /**
         * @var Category $category
         */
        $category = $categoryRepository->find($categoryId);

        if(!$category) return $this->redirectToRoute("admin.category.list");

        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute("admin.category.list");
    }


    /**
     * @Route("admin/category/{id}/update", name="admin.category.update")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function adminUpdateCategory(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categoryRepository = $entityManager->getRepository(Category::class);
        $category = $categoryRepository->find($id);

        if(!$category) return $this->redirectToRoute("admin.category.list");

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush(); 

            return $this->redirectToRoute("admin.category.list");
        }

        return $this->render("admin/category/update.html.twig", array(
            "form" => $form->createView(),
            "category" => $category
        ));
    }

    /**
     * @Route("/admin/category/{id}/create", name= "admin.soncategory.create" ,requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     * @throws \Exception
     */
    public function adminSonCategoryCreate(Request $request, $id)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form -> handleRequest($request);

        $entityManager = $this ->getDoctrine()->getManager();
        $categoryRepository = $entityManager->getRepository(Category::class);

        if($form->isSubmitted() && $form-> isValid()){

            $parentCategory = $categoryRepository->find($id);

            $category->setName($form->get('name')->getData());
            $parentCategory->addChild($category);

            $entityManager->persist($parentCategory);
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirect('/admin/category');
        }

        return $this->render('admin/category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
